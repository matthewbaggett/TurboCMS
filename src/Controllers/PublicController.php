<?php
namespace TurboCMS\Controllers;

use Segura\AppCore\Abstracts\Controller;
use Segura\AppCore\App;
use Slim\Views\Twig;

class PublicController extends Controller
{

    /** @var Twig */
    protected $twig;

    public function __construct()
    {
        /** @var Twig $twig */
        $this->twig = App::Container()->get("view");
    }
}
