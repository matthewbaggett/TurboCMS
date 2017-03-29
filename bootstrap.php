<?php
define("TURBO_ROOT", __DIR__);
define("PUBLIC_ROOT", dirname($_SERVER['SCRIPT_FILENAME']));
define("APP_ROOT", realpath(PUBLIC_ROOT . "/../"));
require_once(APP_ROOT . "/vendor/autoload.php");

\TurboCMS\TurboCMS::Instance()
    ->loadAllRoutes()
    ->getApp()
        ->run();
