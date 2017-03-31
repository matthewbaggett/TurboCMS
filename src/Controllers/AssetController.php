<?php

namespace TurboCMS\Controllers;

use Pekkis\MimeTypes\MimeTypes;
use Segura\AppCore\Abstracts\Controller;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;

class AssetController extends Controller{
    public function getAsset(Request $request, Response $response, $args){
        $assetPath = SITE_ROOT . "/Assets/" . $args['path'];

        if(realpath($assetPath) === $assetPath && file_exists($assetPath)){
          $response = $response->withBody(new Body(fopen('php://temp', 'r+')));
          $response->getBody()->write(file_get_contents($assetPath));

          $mimeTypes = new MimeTypes();
          $assetExtension = pathinfo($assetPath, PATHINFO_EXTENSION);

          $detectedMimeType = $mimeTypes->extensionToMimeType($assetExtension);
          $response = $response->withHeader('Content-Type', $detectedMimeType . ';charset=utf-8');

          return $response;
      }else{
          return $response->withStatus(400);
      }
    }
}