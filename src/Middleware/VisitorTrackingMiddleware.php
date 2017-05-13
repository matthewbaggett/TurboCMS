<?php

namespace TurboCMS\Middleware;

use Dflydev\FigCookies;

use Segura\AppCore\App;
use Slim\Http;
use Thru\UUID\UUID;

class VisitorTrackingMiddleware{


    private $ignoredPaths = [
        "/asset",
        "/image",
        "/demo",
    ];

    public function __invoke(Http\Request $request, Http\Response $response, $next)
    {
        foreach($this->ignoredPaths as $ignoredPath){
            if(stripos($request->getUri()->getPath(), $ignoredPath) === 0){
                return $next($request, $response);
            }
        }
        $cookieName = crc32("Tracking");
        if(FigCookies\FigRequestCookies::get($request, $cookieName)->getValue() == null) {
            $trackingId = UUID::v4();
            $setCookie = FigCookies\SetCookie::create($cookieName)
                ->withValue($trackingId)
                ->rememberForever()
                ->withPath('/');
            $response = FigCookies\FigResponseCookies::set($response, $setCookie);
        }
        /** @var \Predis\Client $redis */
        $redis = App::Container()->get("Redis");

        $visitorId = FigCookies\FigRequestCookies::get($request, $cookieName)->getValue();
        $requestPath = $request->getUri()->__toString();

        $redis->incr("track:{$visitorId}:page_views");
        $redis->hset(
            "track:{$visitorId}:pages",
            $redis->get("track:{$visitorId}:page_views"),
            json_encode(
                [
                    'path' => $requestPath,
                    'method' => $request->getMethod(),
                    'time' => date("Y-m-d H:i:s")
                ],
                JSON_PRETTY_PRINT
            )
        );

        $response = $next($request, $response);

        if(isset($setCookie)){
            $response = FigCookies\FigResponseCookies::set($response, $setCookie);
        }

        return $response;
    }
}