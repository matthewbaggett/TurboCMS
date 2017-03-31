<?php
define("TURBO_ROOT", __DIR__);
if(php_sapi_name() == 'cli'){
    define("PUBLIC_ROOT", __DIR__ . "/../../../public");
}else {
    define("PUBLIC_ROOT", dirname($_SERVER['SCRIPT_FILENAME']));
}
define("APP_ROOT", realpath(PUBLIC_ROOT . "/../"));
define("APP_CORE_NAME", "TurboCMS\\TurboCMS");
require_once(APP_ROOT . "/vendor/autoload.php");

$micrositesApp = \TurboCMS\TurboCMS::Instance()
    ->loadAllRoutes()
    ->getApp();

