<?php

namespace TurboCMS\Controllers;

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

        $fileData = $filesystem->read($args['path']);

        switch($args['size']){
            case 'thumb':
                $width = 250;
                $height = 250;
                break;
            default:
                break;
        }


    }
}