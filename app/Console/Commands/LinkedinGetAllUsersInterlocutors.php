<?php

namespace App\Console\Commands;


use App\Linkedin\Responses\Response;
use App\Repositories\InterlocutorRepository;
use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class LinkedinGetAllUsersInterlocutors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LinkedinGetAllUsersInterlocutors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $user
     * @param array $query_params
     */
    function getConversations($user, $query_params = []): void
    {

        $resp = Response::interlocutors(Api::conversation($user->linkedin_login, $user->linkedin_password)->getConversations($query_params), $user->linkedin_entityUrn);

        if ($resp['success']) {
            $array = (new InterlocutorRepository())->updateOrCreateCollection($resp['data']);
            (new UserRepository())->attachInterlocutors($user->id, $array);
            $this->getConversations($user, ['createdBefore' => $resp['lastActivityAt']]);
        }

    }

    /**
     * @return int
     */
    public function handle(): int
    {

        $users = (new UserRepository())->getLinkedinCredentialsFilledUsers();

        $users->map(function ($user) {

            (new UserRepository())->syncInterlocutors($user->id, []);

            $this->getConversations($user);
        });

        return 1;
    }
}
