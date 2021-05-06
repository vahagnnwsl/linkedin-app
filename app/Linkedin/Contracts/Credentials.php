<?php
namespace App\Linkedin\Contracts;


interface Credentials  {

    /**
     * @param string $login
     * @param string $password
     * @return $this
     */
    public function setCredentials(string $login,string $password): self;
}
