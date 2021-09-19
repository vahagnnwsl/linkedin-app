<?php

namespace App\Linkedin;

use App\Linkedin\Repositories\Auth;
use App\Linkedin\Repositories\Company;
use App\Linkedin\Repositories\Conversation;
use App\Linkedin\Repositories\Invitation;
use App\Linkedin\Repositories\Profile;
use App\Models\Account;
use App\Models\Proxy;

class Api
{

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @return Auth
     */
    public static function auth(Account $account, Proxy $proxy = null): Auth
    {
        return (new Auth())->setCredentials($account,$proxy);
    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @return Conversation
     */
    public static function conversation(Account $account, Proxy $proxy = null): Conversation
    {
        return (new Conversation())->setCredentials($account,$proxy);
    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @return Profile
     */
    public static function profile(Account $account, Proxy $proxy = null): Profile
    {
        return (new Profile())->setCredentials($account, $proxy);
    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @return Invitation
     */
    public static function invitation(Account $account, Proxy $proxy = null): Invitation
    {
        return (new Invitation())->setCredentials($account,$proxy);
    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @return Company
     */
    public static function company(Account $account, Proxy $proxy = null): Company
    {
        return (new Company())->setCredentials($account, $proxy);
    }

}
