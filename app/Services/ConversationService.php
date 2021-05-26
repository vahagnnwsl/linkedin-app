<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Messages;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Conversation as ConversationModel;
use App\Models\Proxy;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\File;

class ConversationService
{


    protected $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    /**
     * @param User $user
     * @param Account $account
     * @param ConversationModel $conversation
     */
   public function getConversationMessages(User $user,Account $account,ConversationModel $conversation){

        $this->recursiveGetConversationMessages($user,$account,$conversation,[]);
   }

    /**
     * @param User $user
     * @param Account $account
     * @param ConversationModel $conversation
     * @param array $query_params
     */
   public function recursiveGetConversationMessages(User $user,Account $account,ConversationModel $conversation,array $query_params = [],$a = 1){

       $response = (new Messages((array)Api::conversation($account->login, $account->password)->getConversationMessages($conversation->entityUrn,$query_params),$conversation->entityUrn))();

     //  File::put(storage_path($a.'.json'),json_encode($response));
       if ($response['success'] && count($response['data'])) {

           $this->messageRepository->updateOrCreateCollection($response['data'], $conversation->id,$user->id, $account->id, $account->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT,true);
           $a++;
           sleep(3);

           $this->recursiveGetConversationMessages($user,$account,$conversation,[
               'createdBefore'=>$response['lastActivityAt']
           ],$a);
       }
   }
}
