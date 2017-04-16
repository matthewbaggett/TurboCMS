<?php

namespace TurboCMS\Mail;

use MicroSites\Models\MailAccountModel;
use MicroSites\Services\MailAccountService;
use MicroSites\Services\UpdaterService;
use Segura\AppCore\App;
use TurboCMS\TurboCMS;

class MailFetch
{

    protected $mailAccountService;

    public function __construct(MailAccountService $mailAccountService)
    {
        $this->mailAccountService = $mailAccountService;
    }

    /**
     * @param MailAccountModel[]|null $mailAccounts
     */
    public function run(array $mailAccounts = null)
    {
        if(!$mailAccounts){
            $mailAccounts = $this->mailAccountService->getAll();
        }
        foreach($mailAccounts as $mailAccount){
            /** @var $mailAccount MailAccountModel */
            $mailAccount->connect();
        }
    }
}