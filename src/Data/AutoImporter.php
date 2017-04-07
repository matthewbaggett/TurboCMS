<?php

namespace TurboCMS\Data;

use TurboCMS\TurboCMS;

class AutoImporter{
    public function run(){
        $connection = TurboCMS::Container()->get("DatabaseConfig")['Default'];

        foreach(TurboCMS::Instance()->getSiteConfigs() as $site => $config){
            $sqlDirPath = APP_ROOT . "/sites/{$site}/SQL";
            echo "Checking for SQL to import: {$site}\n";
            \Kint::dump($config);
            if(file_exists($sqlDirPath) && is_dir($sqlDirPath)){
                $sqlDirListing = [];
                foreach(new \DirectoryIterator($sqlDirPath) as $sqlFile){
                    if(!$sqlFile->isDot() && $sqlFile->getExtension() == 'sql') {
                        $sqlDirListing[$sqlFile->getFilename()] = $sqlFile->getRealPath();
                    }
                }
                ksort($sqlDirListing);
                foreach($sqlDirListing as $sqlFile => $sqlPath){
                    echo " > Running {$sqlFile}...";
                    $importCommand = "mysql -u {$connection['username']} -h {$connection['hostname']} -p{$connection['password']} {$connection['database']} < {$sqlPath}";
                    exec($importCommand);
                    echo " [DONE]\n";
                }
            }
            echo "\n";
        }
    }
}