<?php
namespace TurboCMS\Controllers;

use MicroSites\Models\SitesModel;
use MicroSites\Services\PagesService;
use MicroSites\Services\SitesService;
use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Slim\Views\Twig;

class PublicController extends Controller
{

    /** @var Twig */
    protected $twig;
    /** @var PagesService */
    protected $pagesService;
    /** @var SitesService */
    protected $sitesService;
    /** @var SitesModel */
    protected $site;

    public function __construct()
    {
        /** @var Twig $twig */
        $this->twig = App::Container()->get("view");
        $this->pagesService = App::Container()->get(PagesService::class);
        $this->sitesService = App::Container()->get(SitesService::class);
        $this->site = $this->sitesService->getByField(SitesModel::FIELD_SITENAME, APP_NAME);
    }
}
