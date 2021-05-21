<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Contracts\Credentials;
use App\Models\Proxy;

class Repository implements Credentials
{

    /**
     * @var string
     */
    protected string $login;

    /**
     * @var
     */
    protected  $proxy;

    /**
     * @var string
     */
    protected string $password;


    /**
     * @param string $login
     * @param string $password
     * @return $this
     */
    public function setCredentials(string $login, string $password,Proxy $proxy= null): self
    {
        $this->login = $login;

        $this->password = $password;

        $this->proxy = $proxy;

        return $this;
    }





}
