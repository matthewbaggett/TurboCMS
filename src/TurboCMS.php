<?php

namespace TurboCMS;

use \Segura\AppCore\App;
use Symfony\Component\Yaml\Yaml;

class TurboCMS extends App
{
    private $siteConfigs = [];
    private $micrositeSelected = false;
    private $micrositeConfig = false;

    public function __construct()
    {
        $this->setUp();
        parent::__construct();
        foreach(new \DirectoryIterator(TURBO_ROOT . "/src/Routes") as $file) {
            if(!$file->isDot() && $file->getExtension() == 'php') {
                $this->addRoutePath($file->getRealPath());
            }
        }
        $this->addViewPath(TURBO_ROOT . "/src/Views");
    }

    protected function setUp()
    {
        $this->setUp_parseMicrosites();
        $this->setUp_determineMicrosite();
        $this->setUp_initialiseMicrosite();
    }

    protected function setUp_parseMicrosites()
    {
        $configsToParse = [];
        foreach(new \DirectoryIterator(APP_ROOT . "/sites") as $site){
            if($site->isDir() && file_exists($site->getRealPath() . "/config.yml")){
                $configsToParse[$site->getFilename()] = $site->getRealPath() . "/config.yml";
            }
        }

        foreach($configsToParse as $site => $configPath){
            $this->siteConfigs[$site] = Yaml::parse(file_get_contents($configPath));
        }
    }

    protected function setUp_determineMicrosite()
    {
        if(php_sapi_name() == 'cli'){
            $serverName = 'default';
        }else {
            $serverName = $_SERVER['SERVER_NAME'];
        }
        foreach ($this->siteConfigs as $site => $config) {
            if (in_array($serverName, $config['domains'])) {
                $this->micrositeSelected = $site;
                $this->micrositeConfig = $config;
            }
        }
        if ($this->micrositeSelected === false) {
            die("No microsite configured for domain \"{$serverName}\".\n\n");
        }
    }

    protected function setUp_initialiseMicrosite()
    {
        define("APP_NAME", $this->micrositeSelected);
        define("SITE_ROOT", APP_ROOT . "/sites/" . $this->micrositeSelected);
        $this->addViewPath(SITE_ROOT . "/Views");

    }
}
