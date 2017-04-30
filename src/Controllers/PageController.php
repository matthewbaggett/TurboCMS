<?php

namespace TurboCMS\Controllers;

use MicroSites\Models\BlocksModel;
use MicroSites\Models\PagesModel;
use MicroSites\Services\PagesService;
use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Segura\AppCore\Exceptions\TableGatewayRecordNotFoundException;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class PageController extends Controller
{
    public function getPage(Request $request, Response $response, $args)
    {
        /** @var PagesService $pageService */
        $pageService = App::Container()->get(PagesService::class);
        try {
            $page   = $pageService->getByField(PagesModel::FIELD_URLSLUG, $args['page_slug']);
            if ($page->getStatus() != PagesModel::STATUS_PUBLISHED) {
                return $response->withStatus(404);
            }
            $blocks = $page->fetchRenderableBlockObjects();



            /** @var Twig $twig */
            $twig = App::Container()->get("view");

            return $twig->render($response, 'Pages/Default.html.twig', [
                'page_name' => $page->getTitle(),
                'blocks'    => $blocks,
            ]);
        } catch (TableGatewayRecordNotFoundException $tgrnfe) {
            return $response->withStatus(404);
        }
    }
}
