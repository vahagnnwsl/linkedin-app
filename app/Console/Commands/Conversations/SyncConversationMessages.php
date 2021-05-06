<?php

namespace App\Console\Commands\Conversations;


use App\Linkedin\Repositories\Conversation;
use App\Repositories\AccountRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use App\Linkedin\Responses\Response;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class SyncConversationMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:SyncConversationMessages {user_id} {account_id} {conversation_id}';

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


    public function getMessages($user_id,$account, $conversation, array $query_params = [])
    {


        $response = Response::messages((array)Api::conversation($account->login, $account->password)->getConversationMessages($conversation->entityUrn, $query_params), $conversation->entityUrn);

        if ($response['success']) {

            (new MessageRepository())->updateOrCreateCollection($response['data']->toArray(), $conversation->id,$user_id, $account->id,  $account->entityUrn,MessageRepository::SENDED_STATUS, MessageRepository::RECEIVE_EVENT);

            $this->getMessages($user_id,$account, $conversation, ['createdBefore' => $response['lastActivityAt']]);

        }

    }

    /**
     * @return int
     */
    public function handle()
    {

        $account = (new AccountRepository())->getById($this->argument('account_id'));
        $conversation = (new ConversationRepository())->getById($this->argument('conversation_id'));

        $this->getMessages($this->argument('user_id'),$account, $conversation, []);

        dd($this->arguments());


//        foreach ((new UserRepository())->getLinkedinCredentialsFilledUsers() as $user) {
//
//            $conversations = $user->linkedinConversations;
//
//            foreach ($conversations as $conversation) {
//
//                try {
//
//                    $response = Response::messages((array)Api::conversation($user->linkedin_login, $user->linkedin_password)->getConversationMessages($conversation->entityUrn), $conversation->entityUrn);
//                    (new LinkedinMessageRepository())->updateOrCreateCollection($response, $conversation->id, $conversation->entityUrn, LinkedinMessageRepository::SENDED_STATUS, LinkedinMessageRepository::RECEIVE_EVENT);
//
//                } catch (\Exception $exception) {
//                    $this->error($exception->getMessage());
//                    continue;
//                }
//            }
//        }

        return 1;
    }
}
