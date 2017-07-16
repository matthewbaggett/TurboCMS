<?php

namespace TurboCMS\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class MailController extends PublicController
{
    public function sendMail(Request $request, Response $response, $args)
    {
        \Kint::dump($request->getParsedBody());
    }
}
