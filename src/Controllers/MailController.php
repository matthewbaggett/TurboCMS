<?php

namespace TurboCMS\Controllers;

use Gone\UUID\UUID;
use MicroSites\Models\CustomerMessagesModel;
use MicroSites\Services\CustomerMessagesService;
use Slim\Http\Request;
use Slim\Http\Response;

class MailController extends PublicController
{
    /** @var CustomerMessagesService */
    protected $customerMessageService;

    public function __construct()
    {
        parent::__construct();
        $this->customerMessageService = \Segura\AppCore\App::Container()->get(CustomerMessagesService::class);
    }

    public function sendMail(Request $request, Response $response, $args)
    {
        $customerMessage = CustomerMessagesModel::factory()
            ->setName($request->getParsedBodyParam('name'))
            ->setTelephone($request->getParsedBodyParam('telephone'))
            ->setEmail($request->getParsedBodyParam('email'))
            ->setMessage($request->getParsedBodyParam('message'))
            ->setUuid(UUID::v4())
            ->setDateCreated(date("Y-m-d H:i:s"))
            ->setDeleted("No")
            ->setRead("No")
            ->setSiteId($this->site->getId())
            ->save();

        $this->customerMessageService->send($customerMessage);
    }
}
