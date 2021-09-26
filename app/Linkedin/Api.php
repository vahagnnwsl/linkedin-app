<?php

namespace App\Linkedin;

use App\Linkedin\Repositories\Auth;
use App\Linkedin\Repositories\Company;
use App\Linkedin\Repositories\Conversation;
use App\Linkedin\Repositories\Invitation;
use App\Linkedin\Repositories\Profile;
use App\Models\Account;

class Api
{

    /**
     * @param Account $account
     * @return Auth
     */
    public static function auth(Account $account): Auth
    {
        $proxy = $account->proxy;

        return (new Auth())->setCredentials($account, $proxy);
    }

    /**
     * @param Account $account
     * @return Conversation
     */
    public static function conversation(Account $account): Conversation
    {
        $proxy = $account->proxy;

        return (new Conversation())->setCredentials($account, $proxy);
    }

    /**
     * @param Account $account
     * @return Profile
     */
    public static function profile(Account $account): Profile
    {
        $proxy = $account->proxy;
        return (new Profile())->setCredentials($account, $proxy);
    }

    /**
     * @param Account $account
     * @return Invitation
     */
    public static function invitation(Account $account): Invitation
    {
        $proxy = $account->proxy;
        return (new Invitation())->setCredentials($account, $proxy);
    }

    /**
     * @param Account $account
     * @return Company
     */
    public static function company(Account $account): Company
    {
        $proxy = $account->proxy;
        return (new Company())->setCredentials($account, $proxy);
    }

}
