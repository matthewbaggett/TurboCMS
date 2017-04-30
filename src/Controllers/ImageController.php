<?php

namespace TurboCMS\Controllers;

use Imagine\Gd\Imagine;
use Imagine\Image;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MicroSites\Models\SitesModel;
use MicroSites\Services\SitesService;
use Pekkis\MimeTypes\MimeTypes;
use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Slim\Http\Request;
use Slim\Http\Response;

class ImageController extends Controller
{
    public function getCustomerStorageService(SitesModel $site){
        $storagePath = APP_ROOT . "/sites/{$site->getSiteName()}/Storage";
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }
        $localAdaptor = new Local($storagePath);
        return new Filesystem($localAdaptor);
    }

    public function getImage(Request $request, Response $response, $args)
    {
        $extension    = explode(".", $args['path']);
        $extension    = end($extension);
        $tempName     = rand(1000, 9999) . ".{$extension}";
        $tempFilePath = APP_ROOT . "/tmp/" . $tempName;
        $resizedPath  = SITE_ROOT . "/Storage/Resize/" . $args['size'] . "/" . $args['path'];

        if(isset($args['site'])){
            /** @var SitesService $sitesService */
            $sitesService = App::Container()->get(SitesService::class);
            $site = $sitesService->getByField(SitesModel::FIELD_SITENAME, $args['site']);
            /** @var Filesystem $filesystem */
            $filesystem = $this->getCustomerStorageService($site);
        }else{
            /** @var Filesystem $filesystem */
            $filesystem = App::Container()->get("Storage");
        }

        if (!file_exists($resizedPath) || true) {
            ini_set("memory_limit", "512M");


            /** @var Filesystem $tempFileSystem */
            $tempFileSystem = App::Container()->get("TempStorage");

            $tempFileSystem->writeStream($tempName, $filesystem->readStream($args['path']));

            $imagine = new Imagine();
            $image   = $imagine->open($tempFilePath);

            switch ($args['size']) {
                case 'thumb':
                    $size = new Image\Box(250, 250);
                    $mode = Image\ImageInterface::THUMBNAIL_INSET;
                    break;
                default:
                    if (count(explode("x", $args['size'], 2)) == 2) {
                        $sizeBits = explode("x", $args['size'], 2);
                        $size     = new Image\Box($sizeBits[0], $sizeBits[1]);
                        $mode     = Image\ImageInterface::THUMBNAIL_INSET;
                    }
                    break;
            }

            if (isset($size) && isset($mode)) {
                $image = $image->thumbnail($size, $mode);
            } else {
                die("Cannot display image with invalid resize properties.");
            }


            if (!file_exists(dirname($resizedPath))) {
                mkdir(dirname($resizedPath), 0777, true);
            }
            $image->save($resizedPath);
            unlink($tempFilePath);
        }
        $mimetyper   = new MimeTypes();
        $contentType = $mimetyper->extensionToMimeType($extension);

        $response->getBody()->write(file_get_contents($resizedPath));
        $response = $response->withHeader("Content-type", $contentType);
        return $response;
    }
}
