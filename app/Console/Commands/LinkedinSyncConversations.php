<?php

namespace App\Console\Commands;


use App\Repositories\ConversationRepository;
use App\Repositories\UserRepository;
use App\Linkedin\Responses\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use App\Linkedin\Api;

class LinkedinSyncConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LinkedinSyncConversations';

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

        $resp = Response::conversations(Api::conversation($user->linkedin_login, $user->linkedin_password)->getConversations($query_params), $user->linkedin_entityUrn);

        if ($resp['success']) {

            $conversation_ids = (new ConversationRepository())->updateOrCreateCollection($resp['data']);

            (new UserRepository())->attachConversations($user->id, $conversation_ids);

            $this->getConversations($user, ['createdBefore' => $resp['lastActivityAt']]);
        }
    }


    /**
     * @return int
     */
    public function handle()
    {

        $users = (new UserRepository())->getLinkedinCredentialsFilledUsers();

        $users->map(function ($user) {
            (new UserRepository())->syncConversations($user->id, []);
            $this->getConversations($user);
        });

        return 1;
    }
}
