<?php

namespace TurboCMS\Data;

use MicroSites\Services\UpdaterService;
use Segura\AppCore\App;
use TurboCMS\TurboCMS;

class AutoImporter{

    /** @var UpdaterService */
    private $updaterService;

    public function __construct()
    {
        $this->updaterService = App::Container()->get(UpdaterService::class);
    }

    public function scanForSql($path){
        $results = [];
        foreach(new \DirectoryIterator($path) as $file){
            if(!$file->isDot()) {
                if($file->isDir()){
                    $results = array_merge($results, $this->scanForSql($file->getRealPath()));
                }elseif($file->getExtension() == 'sql'){
                    $results[$file->getRealPath()] = $file->getRealPath();
                }
            }
        }
        ksort($results);
        return $results;
    }

    public function applyScripts($sqlFiles){
        $connection = TurboCMS::Container()->get("DatabaseConfig")['Default'];

        foreach($sqlFiles as $sqlFile){
            echo " > Running {$sqlFile}...";
            try {
                $alreadyApplied = $this->updaterService->updateAlreadyApplied($sqlFile);
            }catch(\Exception $exception){
                $alreadyApplied = false;
            }

            if(!$alreadyApplied) {

                $importCommand = "mysql -u {$connection['username']} -h {$connection['hostname']} -p{$connection['password']} {$connection['database']} < {$sqlFile}  2>&1 | grep -v \"Warning: Using a password\"";
                ob_start();
                exec($importCommand);
                ob_end_clean();
                $update = $this->updaterService->getNewModelInstance();
                $update
                    ->setFile($sqlFile)
                    ->setDateApplied(date("Y-m-d H:i:s"))
                    ->save();
                echo " [DONE]\n";
            }else{
                echo " [SKIPPED]\n";
            }
        }
    }

    public function waitForMySQL()
    {
        $connection = TurboCMS::Container()->get("DatabaseConfig")['Default'];

        $ready = false;
        echo "Waiting for MySQL to come up...";
        while ($ready == false) {
            $conn = @fsockopen($connection['hostname'], $connection['port']);
            if(is_resource($conn)){
                fclose($conn);
                $ready = true;
            }else{
                echo ".";
                usleep(500000);
            }
        }
        echo " [DONE]\n";
    }

    public function run(){
        $generalSqlDirPath = TURBO_ROOT . "/src/SQL/";
        $this->waitForMySQL();
        $sqlFiles = array_values($this->scanForSql($generalSqlDirPath));

        echo "Checking for base SQL to run:\n";
        $this->applyScripts($sqlFiles);
        echo "Complete.\n\n";

        foreach(TurboCMS::Instance()->getSiteConfigs() as $site => $config){
            $sqlDirPath = APP_ROOT . "/sites/{$site}/SQL";
            echo "Checking for SQL to import: {$site}\n";
            if(file_exists($sqlDirPath) && is_dir($sqlDirPath)){
                $sqlDirListing = array_values($this->scanForSql($sqlDirPath));
                $this->applyScripts($sqlDirListing);
            }
            echo "\n";
        }
    }
}