<?php

namespace TurboCMS\Middleware;

use \Predis\Client as Predis;
use MicroSites\Models\SitesModel;
use MicroSites\Services\SitesService;
use Segura\AppCore\Exceptions\TableGatewayException;
use Slim\Http;
use TurboCMS\TurboCMS;

class BandwidthTrackingMiddleware
{
    /** @var Predis */
    private $redis;

    /** @var SitesService */
    private $siteService;

    public function __construct(Predis $redis, SitesService $sitesService)
    {
        $this->redis       = $redis;
        $this->siteService = $sitesService;
    }

    public function __invoke(Http\Request $request, Http\Response $response, $next)
    {
        /** @var $response Http\Response */
        $response = $next($request, $response);
        $length   = $response->getBody()->getSize();

        try {
            $site = $this->siteService->getByField(SitesModel::FIELD_SITENAME, TurboCMS::Instance()->getCurrentSiteName());
            $this->redis->incrby('bandwidth:' . $site->getUuid(), $length);
        } catch (TableGatewayException $tge) {
        }

        return $response;
    }
}
