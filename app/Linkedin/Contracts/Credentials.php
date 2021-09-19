<?php
namespace App\Linkedin\Contracts;


use App\Models\Account;

interface Credentials  {


    /**
     * @param Account $account
     * @return $this
     */
    public function setCredentials(Account $account): self;
}
