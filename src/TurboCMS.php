<?php

namespace TurboCMS;

use \Segura\AppCore\App;
use \Segura\Session\Session;
use Gone\Twig\GravatarExtension;
use Gone\Twig\InflectExtension;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MicroSites\Models\SitesDomainsModel;
use MicroSites\Models\SitesModel;
use MicroSites\Models\SitesSettingsModel;
use MicroSites\Services\MailAccountService;
use MicroSites\Services\SitesDomainsService;
use MicroSites\Services\SitesService;
use MicroSites\Services\SitesSettingsService;
use Monolog\Logger;
use Segura\AppCore\Exceptions\TableGatewayException;
use Segura\AppCore\Services\AutoImporterService;
use Slim;
use Slim\Views\Twig;
use TurboCMS\Mail\MailFetch;
use TurboCMS\Middleware\BandwidthTrackingMiddleware;
use TurboCMS\Middleware\VisitorTrackingMiddleware;
use TurboCMS\Services\GeoIPLookup;
use Zend\Db\Sql\Where;

class TurboCMS extends App
{
    private $siteConfigs       = [];
    private $micrositeSelected = false;
    /** @var array  */
    private $micrositeConfig   = [];

    public function __construct()
    {
        parent::__construct();
        if (php_sapi_name() != 'cli') {
            $this->setUp_determineMicrosite();
            $this->setUp_initialiseMicrosite();
        }

        foreach (new \DirectoryIterator(TURBO_ROOT . "/src/Routes") as $file) {
            if (!$file->isDot() && $file->getExtension() == 'php') {
                $this->addRoutePath($file->getRealPath());
            }
        }
        $this->addViewPath(TURBO_ROOT . "/src/Views");
        /** @var Twig $twig */
        $twig = $this->getContainer()->get("view");

        foreach ($this->getCurrentSiteConstants() as $constant => $value) {
            $twig->offsetSet($constant, $value);
        }

        $this->container['cache'] = function () {
            return new Slim\HttpCache\CacheProvider();
        };

        $this->container['Storage'] = function (Slim\Container $container) {
            $storagePath = SITE_ROOT . "/Storage";
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
            $localAdaptor = new Local($storagePath);
            return new Filesystem($localAdaptor);
        };

        $this->container['TempStorage'] = function (Slim\Container $container) {
            $storagePath = APP_ROOT . "/tmp";
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0777, true);
            }
            $localAdaptor = new Local($storagePath);
            return new Filesystem($localAdaptor);
        };

        $this->container[MailFetch::class] = function (Slim\Container $container) {
            return new MailFetch($container->get(MailAccountService::class));
        };

        $this->container[GeoIPLookup::class] = function (Slim\Container $container) {
            return new GeoIPLookup();
        };

        $this->container->get(AutoImporterService::class)
            ->addSqlPath(TURBO_ROOT . "/src/SQL");

        foreach (new \DirectoryIterator(APP_ROOT . "/sites") as $site) {
            if ($site->isDir()) {
                if (file_exists($site->getRealPath() . "/AppContainer.php")) {
                    require($site->getRealPath() . "/AppContainer.php");
                }
                if (file_exists($site->getRealPath() . "/SQL")) {
                    $this->container->get(AutoImporterService::class)
                        ->addSqlPath($site->getRealPath() . "/SQL");
                }
            }
        }

        $this->container[VisitorTrackingMiddleware::class] = function (Slim\Container $container) {
            return new VisitorTrackingMiddleware();
        };

        $this->container[BandwidthTrackingMiddleware::class] = function (Slim\Container $container) {
            return new BandwidthTrackingMiddleware(
                $container->get("Redis"),
                $container->get(SitesService::class)
            );
        };

        $this->app->add($this->container->get(VisitorTrackingMiddleware::class));
        $this->app->add($this->container->get(BandwidthTrackingMiddleware::class));
        //$this->app->add(new Slim\HttpCache\Cache('public', 86400));

        $twig->addExtension(new InflectExtension());
        $twig->addExtension(new GravatarExtension());
        $twig->addExtension(new \Kint_TwigExtension());

        if (php_sapi_name() != 'cli') {
            $session = $this->getContainer()->get(Session::class);
        }
    }

    protected function setUp_determineMicrosite()
    {
        if (php_sapi_name() == 'cli') {
            $serverName = 'default';
        } else {
            if (isset($_SERVER['HTTP_HOST'])) {
                $serverName = $_SERVER['HTTP_HOST'];
            } else {
                $serverName = $_SERVER['SERVER_NAME'];
            }
        }

        $container = $this->getContainer();
        /** @var SitesDomainsService $domainService */
        $domainService = $container->get(SitesDomainsService::class);
        /** @var SitesService $sitesService */
        $sitesService = $container->get(SitesService::class);
        try {
            $siteDomain              = $domainService->getByField(SitesDomainsModel::FIELD_DOMAIN, $serverName);
            $this->micrositeSelected = $sitesService->getById($siteDomain->getSiteId())->getSiteName();
            $this->micrositeConfig   = [];
        } catch (TableGatewayException $tableGatewayException) {
        }

        if ($this->micrositeSelected === false && php_sapi_name() != 'cli') {
            die("No microsite configured for domain \"{$serverName}\".\n\n");
        }
    }

    protected function setUp_initialiseMicrosite()
    {
        if (!defined("APP_NAME")) {
            define("APP_NAME", $this->micrositeSelected);
        }
        if (!defined("SITE_ROOT")) {
            define("SITE_ROOT", APP_ROOT . "/sites/" . $this->micrositeSelected);
        }
        $this->addViewPath(SITE_ROOT . "/Views");
        if (file_exists(SITE_ROOT . "/Routes")) {
            foreach (new \DirectoryIterator(SITE_ROOT . "/Routes") as $file) {
                if (!$file->isDot() && $file->getExtension() == 'php') {
                    $this->addRoutePath($file->getRealPath());
                }
            }
        }
    }

    /**
     * @return \Interop\Container\ContainerInterface
     */
    public function getContainer(): \Interop\Container\ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param bool $doNotUseStaticInstance
     *
     * @return TurboCMS
     */
    public static function Instance($doNotUseStaticInstance = false)
    {
        return parent::Instance($doNotUseStaticInstance);
    }

    /**
     * @param \Interop\Container\ContainerInterface $container
     *
     * @return TurboCMS
     */
    public function setContainer(\Interop\Container\ContainerInterface $container): TurboCMS
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return Logger
     */
    public function getMonolog(): Logger
    {
        return $this->monolog;
    }

    /**
     * @param Logger $monolog
     *
     * @return TurboCMS
     */
    public function setMonolog(Logger $monolog): TurboCMS
    {
        $this->monolog = $monolog;
        return $this;
    }

    /**
     * @return SitesModel|null
     */
    public function getCurrentSite() //: ?SitesModel
    {
        /** @var SitesService $siteService */
        $siteService = $this->getContainer()->get(SitesService::class);
        try {
            return $siteService->getByField(SitesModel::FIELD_SITENAME, $this->getCurrentSiteName());
        } catch (\Exception $exception) {
            return null;
        }
    }

    public function getSiteByName($name)
    {
        /** @var SitesService $siteService */
        $siteService = $this->getContainer()->get(SitesService::class);
        try {
            return $siteService->getByField(SitesModel::FIELD_SITENAME, $name);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getSiteConfigs(): array
    {
        return $this->siteConfigs;
    }

    public function getSiteConfig($key): array
    {
        return isset($this->siteConfigs[$key]) ? $this->siteConfigs[$key] : false;
    }

    public function getCurrentSiteName(): string
    {
        return $this->micrositeSelected;
    }

    public function getCurrentSiteConfig(): array
    {
        return $this->micrositeConfig;
    }

    public function getCurrentSiteConstants(): array
    {
        $site      = $this->getCurrentSite();
        $constants = [];
        if (isset($this->getCurrentSiteConfig()['constants'])) {
            $constants = array_merge($constants, $this->getCurrentSiteConfig()['constants']);
        }
        /** @var SitesSettingsService $siteSettingService */
        $siteSettingService = $this->getContainer()->get(SitesSettingsService::class);
        if ($site) {
            $siteSettings = $siteSettingService->getAll(
                null,
                null,
                [
                    function (Where $where) use ($site) {
                        $where->equalTo(SitesSettingsModel::FIELD_SITEID, $site->getId());
                    }
                ]
            );
            foreach ($siteSettings as $siteSetting) {
                if (substr($siteSetting->getKey(), -2, 2) == '[]') {
                    $constants[substr($siteSetting->getKey(), 0, -2)][] = $siteSetting->getValue();
                } else {
                    $constants[$siteSetting->getKey()] = $siteSetting->getValue();
                }
            }
        }
        return $constants;
    }

    /**
     * @param array $siteConfigs
     *
     * @return TurboCMS
     */
    public function setSiteConfigs(array $siteConfigs): TurboCMS
    {
        $this->siteConfigs = $siteConfigs;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMicrositeSelected(): bool
    {
        return $this->micrositeSelected;
    }

    /**
     * @param bool $micrositeSelected
     *
     * @return TurboCMS
     */
    public function setMicrositeSelected(bool $micrositeSelected): TurboCMS
    {
        $this->micrositeSelected = $micrositeSelected;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMicrositeConfig(): bool
    {
        return $this->micrositeConfig;
    }

    /**
     * @param bool $micrositeConfig
     *
     * @return TurboCMS
     */
    public function setMicrositeConfig(bool $micrositeConfig): TurboCMS
    {
        $this->micrositeConfig = $micrositeConfig;
        return $this;
    }
}
