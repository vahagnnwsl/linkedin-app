<?php

namespace App\Linkedin;

use App\Linkedin\Repositories\Conversation;
use App\Linkedin\Repositories\Profile;
use App\Linkedin\Repositories\Auth;
use Illuminate\Support\Facades\Facade;

class Linkedin
{

    /**
     * @return Auth
     */
    public function authRepository() :Auth
    {
        return  new Auth();
    }
}
