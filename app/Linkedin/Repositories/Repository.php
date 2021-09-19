<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Contracts\Credentials;
use App\Models\Account;
use App\Models\Proxy;

class Repository implements Credentials
{

    protected Account $account;


    protected  $proxy;


    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @return $this
     */
    public function setCredentials(Account $account,Proxy $proxy = null): self
    {

        $this->account = $account;

        $this->proxy = $proxy;

        return $this;
    }





}
