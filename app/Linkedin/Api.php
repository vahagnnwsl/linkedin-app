<?php

namespace App\Linkedin;

use App\Linkedin\Repositories\Auth;
use App\Linkedin\Repositories\Company;
use App\Linkedin\Repositories\Conversation;
use App\Linkedin\Repositories\Invitation;
use App\Linkedin\Repositories\Profile;
use App\Models\Proxy;

class Api
{

    /**
     * @param string $login
     * @param string $password
     * @return Auth|Repositories\Repository
     */
    public static function auth(string $login, string $password): Auth
    {
        return (new Auth())->setCredentials($login, $password);
    }

    /**
     * @param string $login
     * @param string $password
     * @param Proxy|null $proxy
     * @return Conversation
     */
    public static function conversation(string $login, string $password, Proxy $proxy = null): Conversation
    {
        return (new Conversation())->setCredentials($login, $password,$proxy);
    }

    /**
     * @param string $login
     * @param string $password
     * @param Proxy|null $proxy
     * @return Profile
     */
    public static function profile(string $login, string $password, Proxy $proxy = null): Profile
    {
        return (new Profile())->setCredentials($login, $password, $proxy);
    }

    /**
     * @param string $login
     * @param string $password
     * @return Invitation
     */
    public static function invitation(string $login, string $password): Invitation
    {
        return (new Invitation())->setCredentials($login, $password);
    }


    /**
     * @param string $login
     * @param string $password
     * @param Proxy|null $proxy
     * @return Company
     */
    public static function company(string $login, string $password, Proxy $proxy = null): Company
    {
        return (new Company())->setCredentials($login, $password, $proxy);
    }

}
