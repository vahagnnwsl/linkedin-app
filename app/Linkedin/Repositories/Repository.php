<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Contracts\Credentials;

class Repository implements Credentials
{

    /**
     * @var string
     */
    protected string $login;

    /**
     * @var string
     */
    protected string $password;


    /**
     * @param string $login
     * @param string $password
     * @return $this
     */
    public function setCredentials(string $login, string $password): self
    {
        $this->login = $login;

        $this->password = $password;

        return $this;
    }





}
