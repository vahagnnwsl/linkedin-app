<?php

namespace App\Console\Commands;


use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use App\Linkedin\Responses\Response;
use Illuminate\Console\Command;
use App\Linkedin\Api;

class LinkedinSyncMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:LinkedinSyncMessages';

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
     * @return int
     */
    public function handle()
    {


        foreach ((new UserRepository())->getLinkedinCredentialsFilledUsers() as $user) {

            $conversations = $user->linkedinConversations;

            foreach ($conversations as $conversation) {

                try {

                    $response = Response::messages((array)Api::conversation($user->linkedin_login, $user->linkedin_password)->getConversationMessages($conversation->entityUrn), $conversation->entityUrn);
                    (new LinkedinMessageRepository())->updateOrCreateCollection($response, $conversation->id, $conversation->entityUrn, LinkedinMessageRepository::SENDED_STATUS, LinkedinMessageRepository::RECEIVE_EVENT);

                } catch (\Exception $exception) {
                    $this->error($exception->getMessage());
                    continue;
                }
            }
        }

        return 1;
    }
}
