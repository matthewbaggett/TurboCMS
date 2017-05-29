<?php

namespace TurboCMS\Middleware;

use Dflydev\FigCookies;

use Gone\UUID\UUID;
use MicroSites\Models\SitesModel;
use MicroSites\Services\SitesService;
use Slim\Http;
use TurboCMS\TurboCMS;

class VisitorTrackingMiddleware
{
    private $ignoredPaths = [
        "/asset",
        "/image",
        "/demo",
    ];

    public function __invoke(Http\Request $request, Http\Response $response, $next)
    {
        foreach ($this->ignoredPaths as $ignoredPath) {
            if (stripos($request->getUri()->getPath(), $ignoredPath) === 0) {
                return $next($request, $response);
            }
        }
        $cookieName = crc32("Tracking");
        if (FigCookies\FigRequestCookies::get($request, $cookieName)->getValue() == null) {
            $trackingId = UUID::v4();
            $setCookie  = FigCookies\SetCookie::create($cookieName)
                ->withValue($trackingId)
                ->rememberForever()
                ->withPath('/');
            $response = FigCookies\FigResponseCookies::set($response, $setCookie);
        }
        /** @var \Predis\Client $redis */
        $redis = TurboCMS::Container()->get("Redis");
        /** @var SitesService $siteService */
        $siteService = TurboCMS::Container()->get(SitesService::class);

        $visitorUuid = FigCookies\FigRequestCookies::get($request, $cookieName)->getValue();
        $site        = $siteService->getByField(SitesModel::FIELD_SITENAME, TurboCMS::Instance()->getCurrentSiteName());
        $requestPath = $request->getUri()->__toString();

        $userAgent = $request->hasHeader('HTTP_USER_AGENT') ? $request->getHeader('HTTP_USER_AGENT')[0] : null;
        $language  = $request->hasHeader('HTTP_ACCEPT_LANGUAGE') ? $request->getHeader('HTTP_ACCEPT_LANGUAGE')[0] : null;
        $ipAddress = $request->hasHeader('HTTP_X_FORWARDED_FOR') ? $request->getheader('HTTP_X_FORWARDED_FOR')[0] : null;

        $redis->incr("track:{$visitorUuid}:page_views");
        $redis->hset(
            "track:{$visitorUuid}:pages",
            $redis->get("track:{$visitorUuid}:page_views"),
            json_encode(
                [
                    'path'      => $requestPath,
                    'siteUuid'  => $site->getUuid(),
                    'method'    => $request->getMethod(),
                    'time'      => date("Y-m-d H:i:s"),
                    'userAgent' => $userAgent,
                    'language'  => $language,
                    'ipAddress' => $ipAddress,
                ],
                JSON_PRETTY_PRINT
            )
        );

        $response = $next($request, $response);

        if (isset($setCookie)) {
            $response = FigCookies\FigResponseCookies::set($response, $setCookie);
        }

        return $response;
    }
}
