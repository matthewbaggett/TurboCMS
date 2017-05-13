<?php

namespace TurboCMS\Middleware;

use Segura\AppCore\App;
use Slim\Http;
use Thru\UUID\UUID;

class VisitorTrackingMiddleware{
    public function __invoke(Http\Request $request, Http\Response $response, $next)
    {

        /** @var Http\Response $response */
        $response = $next($request, $response);
        /** @var \Predis\Client $redis */
        $redis = App::Container()->get("Redis");
        /** @var Http\Cookies $cookies */
        $cookies = App::Container()->get("Cookies");

        if(!$cookies->get("Tracking")) {
            $cookies->set("Tracking", UUID::v4());
        }

        \Kint::dump(
            $request->getCookieParams(),
            $request->getHeaders(),
            $cookies
        );

        $visitorId = $cookies->get("Tracking");
        $redis->incr("track:{$visitorId}:page_views");


        return $response;
    }
}