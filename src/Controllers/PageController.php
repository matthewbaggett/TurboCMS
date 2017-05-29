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
use Zend\Db\Sql\Where;

class PageController extends Controller
{
    /** @var PagesService $pageService */
    protected $pageService;

    public function __construct()
    {
        $this->pageService = App::Container()->get(PagesService::class);
    }

    public function previewPage(Request $request, Response $response, $args)
    {
        try {
            $page = $this->pageService->getByField(PagesModel::FIELD_UUID, $args['page_uuid']);
            if (!$this->canPreview($page)) {
                return $response->withStatus(404);
            }
            $this->pageService->trackView($page);
            return $this->renderPage($page, $response);
        } catch (TableGatewayRecordNotFoundException $tgrnfe) {
            return $response->withStatus(404);
        }
    }
    public function getPage(Request $request, Response $response, $args)
    {
        $site = TurboCMS::Instance()->getCurrentSite();
        try {
            if(isset($args['page_slug'])){
                $pageSlug = $args['page_slug'];
            }else{
                $pageSlug = '';
            }
            $pages = $this->pageService->getAll(1, 0, [
                function (Where $where) use ($pageSlug) {
                    $where->equalTo(PagesModel::FIELD_URLSLUG, $pageSlug);
                },
                function (Where $where) use ($site) {
                    $where->equalTo(PagesModel::FIELD_SITEID, $site->getId());
                }
            ]);
            $page = reset($pages);
            $this->pageService->trackView($page);
            return $this->renderPage($page, $response);
        } catch (TableGatewayRecordNotFoundException $tgrnfe) {
            return $response->withStatus(404);
        }
    }

    public function renderPage(PagesModel $page, Response $response)
    {
        if (!$page->isPublished() && !$this->canPreview($page)) {
            return $response->withStatus(404);
        }
        $blocks = $page->fetchRenderableBlockObjects();

        /** @var Twig $twig */
        $twig = App::Container()->get("view");
        $site = $page->fetchSiteObject();

        return $twig->render($response, $page->getPageTypeId() ? $page->fetchPageTypeObject()->getTemplate() : 'Pages/Default.html.twig', [
            'site'      => $site,
            'page_name' => $page->getTitle(),
            'page'      => $page,
            'blocks'    => $blocks,
        ]);
    }

    protected function canPreview(PagesModel $page)
    {
        $userId      = Session::get(UsersModel::FIELD_ID);
        $isSiteOwner = false;
        if ($userId) {
            try {
                $usersService = TurboCMS::Container()->get(UsersService::class);
                /** @var UsersModel $user */
                $user = $usersService->getById($userId);
                $site = $page->fetchSiteObject();
                foreach ($user->getSites() as $availableSite) {
                    /** @var $availableSite SitesModel */
                    if ($availableSite->getId() == $site->getId()) {
                        $isSiteOwner = true;
                    }
                }
            } catch (TableGatewayException $tableGatewayException) {
            }
        }
        return $isSiteOwner;
    }
}
