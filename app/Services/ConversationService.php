<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Messages;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\Proxy;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\File;
use JetBrains\PhpStorm\Pure;

class ConversationService
{


    protected MessageRepository $messageRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
    }

    /**
     * @param User $user
     * @param Account $account
     * @param Conversation $conversation
     */
   public function getConversationMessages(User $user,Account $account,Conversation $conversation){

       $proxy = $account->proxy;

       $this->recursiveGetConversationMessages($user,$account,$proxy,$conversation,[]);
   }

    /**
     * @param User $user
     * @param Account $account
     * @param Conversation $conversation
     * @param array $query_params
     */
   public function recursiveGetConversationMessages(User $user,Account $account,Proxy $proxy,Conversation $conversation,array $query_params = [],$i = 1){

       $response = Api::conversation($account,$proxy)->getConversationMessages($conversation->entityUrn,$query_params);

       if ($response['success']){
           $response = Messages::invoke($response['data'],$conversation->entityUrn);
       }

       if ($response['success'] && count($response['data'])) {
           $this->messageRepository->updateOrCreateCollection($response['data'], $conversation->id,$user->id, $account->id, $account->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT,true);
           $i++;
           sleep(3);
           $this->recursiveGetConversationMessages($user,$account,$proxy,$conversation,[
               'createdBefore'=>$response['lastActivityAt']
           ],$i);
       }
   }
}
