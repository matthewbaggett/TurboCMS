<?php

namespace TurboCMS;

use \Segura\AppCore\App;
use \Segura\Session\Session;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MicroSites\Services\MailAccountService;
use Monolog\Logger;
use Slim;
use Slim\Views\Twig;
use Symfony\Component\Yaml\Yaml;
use TurboCMS\Mail\MailFetch;

class TurboCMS extends App
{
    private $siteConfigs       = [];
    private $micrositeSelected = false;
    private $micrositeConfig   = false;

    public function __construct()
    {
        $this->setUp();
        parent::__construct();
        foreach (new \DirectoryIterator(TURBO_ROOT . "/src/Routes") as $file) {
            if (!$file->isDot() && $file->getExtension() == 'php') {
                $this->addRoutePath($file->getRealPath());
            }
        }
        $this->addViewPath(TURBO_ROOT . "/src/Views");
        /** @var Twig $twig */
        $twig = $this->getContainer()->get("view");
        if (isset($this->micrositeConfig['constants']) && count($this->micrositeConfig['constants']) > 0) {
            foreach ($this->micrositeConfig['constants'] as $constant => $value) {
                $twig->offsetSet($constant, $value);
            }
        }

        $this->container[Session::class] = function (Slim\Container $container) {
            return Session::start($container->get('Redis'));
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

        foreach (new \DirectoryIterator(APP_ROOT . "/sites") as $site) {
            if ($site->isDir()) {
                if (file_exists($site->getRealPath() . "/AppContainer.php")) {
                    require($site->getRealPath() . "/AppContainer.php");
                }
            }
        }

        if (php_sapi_name() != 'cli') {
            $session = $this->getContainer()->get(Session::class);
        }
    }

    protected function setUp()
    {
        $this->setUp_parseMicroSites();
        $this->setUp_determineMicrosite();
        $this->setUp_initialiseMicrosite();
    }

    protected function setUp_parseMicroSites()
    {
        $configsToParse = [];
        foreach (new \DirectoryIterator(APP_ROOT . "/sites") as $site) {
            if ($site->isDir() && file_exists($site->getRealPath() . "/config.yml")) {
                $configsToParse[$site->getFilename()] = $site->getRealPath() . "/config.yml";
            }
        }

        foreach ($configsToParse as $site => $configPath) {
            $this->siteConfigs[$site] = Yaml::parse(file_get_contents($configPath));
        }
    }

    protected function setUp_determineMicrosite()
    {
        if (php_sapi_name() == 'cli') {
            $serverName = 'default';
        } else {
            $serverName = $_SERVER['SERVER_NAME'];
        }
        foreach ($this->siteConfigs as $site => $config) {
            if (in_array($serverName, $config['domains'])) {
                $this->micrositeSelected = $site;
                $this->micrositeConfig   = $config;
            }
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
        return parent::Instance($doNotUseStaticInstance); // TODO: Change the autogenerated stub
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
     * @return array
     */
    public function getSiteConfigs(): array
    {
        return $this->siteConfigs;
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
