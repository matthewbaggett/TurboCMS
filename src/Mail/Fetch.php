<?php

namespace TurboCMS\Mail;

use MicroSites\Models\MailAccountModel;
use MicroSites\Services\MailAccountService;
use MicroSites\Services\UpdaterService;
use Segura\AppCore\App;
use TurboCMS\TurboCMS;

class Fetch
{

    protected $mailAccountService;

    public function __construct(
        MailAccountService $mailAccountService
    )
    {
        $this->mailAccountService = $mailAccountService;
    }
    public function run()
    {
        foreach($this->mailAccountService->getAll() as $mailAccount){
            /** @var $mailAccount MailAccountModel */
            $mailAccount->connect();
        }
    }
}