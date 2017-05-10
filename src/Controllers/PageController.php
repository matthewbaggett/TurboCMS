<?php

namespace TurboCMS\Controllers;

use MicroSites\Models\PagesModel;
use MicroSites\Models\SitesModel;
use MicroSites\Models\UsersModel;
use MicroSites\Services\PagesService;
use MicroSites\Services\UsersService;
use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Segura\AppCore\Exceptions\TableGatewayException;
use Segura\AppCore\Exceptions\TableGatewayRecordNotFoundException;
use Segura\Session\Session;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TurboCMS\TurboCMS;

class PageController extends Controller
{
    public function getPage(Request $request, Response $response, $args)
    {
        /** @var PagesService $pageService */
        $pageService = App::Container()->get(PagesService::class);
        try {
            // @TODO: This will allow other sites to view the same page.. Whoops! Fixme!
            $page   = $pageService->getByField(PagesModel::FIELD_URLSLUG, $args['page_slug']);
            return $this->renderPage($page, $response);
        } catch (TableGatewayRecordNotFoundException $tgrnfe) {
            return $response->withStatus(404);
        }
    }

    public function renderPage(PagesModel $page, Response $response)
    {
        $userId = Session::get(UsersModel::FIELD_ID);
        $isSiteOwner = false;
        if($userId){
            try {
                $usersService = TurboCMS::Container()->get(UsersService::class);
                /** @var UsersModel $user */
                $user = $usersService->getById($userId);
                $site = $page->fetchSiteObject();
                foreach($user->getSites() as $availableSite){
                    /** @var $availableSite SitesModel */
                    if($availableSite->getId() == $site->getId()){
                        $isSiteOwner = true;
                    }
                }
            } catch (TableGatewayException $tableGatewayException) {
            }

        }
        if (!$page->isPublished() && !$isSiteOwner) {
            return $response->withStatus(404);
        }
        $blocks = $page->fetchRenderableBlockObjects();

        /** @var Twig $twig */
        $twig = App::Container()->get("view");

        return $twig->render($response, 'Pages/Default.html.twig', [
            'page_name' => $page->getTitle(),
            'blocks'    => $blocks,
        ]);
    }
}
