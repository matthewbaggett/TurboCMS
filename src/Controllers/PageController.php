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

class PageController extends Controller{
    public function getPage(Request $request, Response $response, $args){
        /** @var PagesService $pageService */
        $pageService = App::Container()->get(PagesService::class);
        try {
            $page = $pageService->getByField(PagesModel::FIELD_URLSLUG, $args['page_slug']);
            $blocks = $page->fetchBlockObjects(BlocksModel::FIELD_ORDER, 'ASC');
            \Kint::dump($blocks);
        }catch (TableGatewayRecordNotFoundException $tgrnfe){
            return $response->withStatus(404);
        }

    }
}