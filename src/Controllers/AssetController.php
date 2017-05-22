<?php

namespace TurboCMS\Controllers;

use Pekkis\MimeTypes\MimeTypes;
use Segura\AppCore\Abstracts\Controller;
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\HttpCache\Cache;
use Slim\HttpCache\CacheProvider;
use TurboCMS\TurboCMS;

class AssetController extends Controller
{

    public function getAsset(Request $request, Response $response, $args)
    {
        /** @var $cache CacheProvider */
        $cache = TurboCMS::Container()->get('cache');



        if (isset($args['site'])) {
            $assetPath =  APP_ROOT . "/sites/" . $args['site'] . "/Assets/" . $args['path'];
        } else {
            $assetPath = SITE_ROOT . "/Assets/" . $args['path'];
        }

        if (realpath($assetPath) === $assetPath && file_exists($assetPath)) {
            $response = $response->withBody(new Body(fopen('php://temp', 'r+')));
            $response->getBody()->write(file_get_contents($assetPath));

            $mimeTypes      = new MimeTypes();
            $assetExtension = pathinfo($assetPath, PATHINFO_EXTENSION);

            $detectedMimeType = $mimeTypes->extensionToMimeType($assetExtension);
            $response         = $response->withHeader('Content-Type', $detectedMimeType . ';charset=utf-8');
            $response = $cache->allowCache($response, 'public');
            $response = $cache->withExpires($response, "+1 day");
            $response = $cache->withEtag($response, crc32($request->getUri()));
            //!\Kint::dump($response->getHeaders());exit;
            return $response;
        } else {
            return $response->withStatus(400);
        }
    }
}
