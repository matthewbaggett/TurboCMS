<?php

\Segura\AppCore\Router\Router::Instance()
    ->addRoute(
        \Segura\AppCore\Router\Route::Factory()
            ->setRouterPattern("/{page_slug}")
            ->setHttpMethod('GET')
            ->setCallback(\TurboCMS\Controllers\PageController::class . ":getPage")
            ->setName("Read a page")
            ->setWeight(99)
    )
    ->addRoute(
        \Segura\AppCore\Router\Route::Factory()
            ->setRouterPattern("/")
            ->setHttpMethod('GET')
            ->setCallback(\TurboCMS\Controllers\PageController::class . ":getPage")
            ->setName("Read a page")
            ->setWeight(99)
    )
    ->addRoute(
        \Segura\AppCore\Router\Route::Factory()
            ->setRouterPattern("/preview/{page_uuid}")
            ->setHttpMethod('GET')
            ->setCallback(\TurboCMS\Controllers\PageController::class . ":previewPage")
            ->setName("Preview a page")
    );
