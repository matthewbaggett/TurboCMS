<?php

\Segura\AppCore\Router\Router::Instance()
    ->addRoute(
        \Segura\AppCore\Router\Route::Factory()
            ->setRouterPattern("/read/{page_slug}")
            ->setHttpMethod('GET')
            ->setCallback(\TurboCMS\Controllers\PageController::class . ":getPage")
            ->setName("Read a page")
    );