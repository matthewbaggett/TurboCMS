<?php

namespace TurboCMS\Controllers;

use Imagine\Gd\Imagine;
use League\Flysystem\Filesystem;
use Pekkis\MimeTypes\MimeTypes;
use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Slim\Http\Request;
use Slim\Http\Response;

class ImageController extends Controller
{
    public function getImage(Request $request, Response $response, $args)
    {
        $extension    = explode(".", $args['path']);
        $extension    = end($extension);
        $tempName     = rand(1000, 9999) . ".{$extension}";
        $tempFilePath = APP_ROOT . "/tmp/" . $tempName;
        $resizedPath  = SITE_ROOT . "/Storage/Resize/" . $args['size'] . "/" . $args['path'];

        if (!file_exists($resizedPath)) {
            ini_set("memory_limit", "512M");

            /** @var Filesystem $filesystem */
            $filesystem = App::Container()->get("Storage");
            /** @var Filesystem $tempFileSystem */
            $tempFileSystem = App::Container()->get("TempStorage");

            $tempFileSystem->writeStream($tempName, $filesystem->readStream($args['path']));

            $imagine = new Imagine();
            $image   = $imagine->open($tempFilePath);

            switch ($args['size']) {
                case 'thumb':
                    $size = new \Imagine\Image\Box(250, 250);
                    $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                    break;
                default:
                    break;
            }

            if ($size && $mode) {
                $image = $image->thumbnail($size, $mode);
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
