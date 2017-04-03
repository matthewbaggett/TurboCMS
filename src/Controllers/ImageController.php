<?php

namespace TurboCMS\Controllers;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;
use League\Flysystem\Filesystem;
use Pekkis\MimeTypes\MimeTypes;
use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

class ImageController extends Controller{
    public function getImage(Request $request, Response $response, $args){
        \Kint::dump($args);

        /** @var Filesystem $filesystem */
        $filesystem = App::Container()->get("Storage");


        $image = ImageManagerStatic::make(SITE_ROOT . "/Storage/" . $args['path']);
        switch($args['size']){
            case 'thumb':
                $width = 250;
                $height = 250;
                break;
            default:
                break;
        }

        $image->resize($width, $height);
        $resizedPath = SITE_ROOT . "/Storage/Resize/" . $size . $args['path'];
        if(!file_exists(dirname($resizedPath))){
            mkdir(dirname($resizedPath), 0777, true);
        }
        $image->save($resizedPath);

        die(":D");


    }
}