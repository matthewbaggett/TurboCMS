<?php

\Segura\AppCore\Router\Router::Instance()
    ->addRoute(
        \Segura\AppCore\Router\Route::Factory()
            ->setRouterPattern("/asset/{path:.*}")
            ->setHttpMethod('GET')
            ->setCallback(\TurboCMS\Controllers\AssetController::class . ":getAsset")
            ->setName("Get a cacheable asset")
    );