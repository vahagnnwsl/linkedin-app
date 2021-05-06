<?php

namespace App\Linkedin;

use App\Linkedin\Repositories\Auth;
use App\Linkedin\Repositories\Conversation;
use App\Linkedin\Repositories\Invitation;
use App\Linkedin\Repositories\Profile;

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
     * @return Conversation|Repositories\Repository
     */
    public static function conversation(string $login, string $password): Conversation
    {
        return (new Conversation())->setCredentials($login, $password);
    }

    /**
     * @param string $login
     * @param string $password
     * @return Profile
     */
    public static function profile(string $login, string $password): Profile
    {
        return (new Profile())->setCredentials($login, $password);
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

}
