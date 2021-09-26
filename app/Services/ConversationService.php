<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Messages;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\MessageRepository;


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
     * @param bool $isLast
     */
    public function getConversationMessages(User $user, Account $account, Conversation $conversation, bool $isLast = false)
    {
        $lastActivate = null;
        if ($isLast) {
            $lastMessage = $conversation->messages()->orderBy('date','DESC')->first();
            if ($lastMessage && $lastMessage->date){
                $lastActivate = $lastMessage->date->timestamp * 1000;
            }
        }

        $this->recursiveGetConversationMessages($user, $account, $conversation, [], $lastActivate);
    }

    /**
     * @param User $user
     * @param Account $account
     * @param Conversation $conversation
     * @param array $query_params
     * @param $proxy
     */
    public function recursiveGetConversationMessages(User $user, Account $account, Conversation $conversation, array $query_params, $lastActivate = null)
    {

        $response = Api::conversation($account)->getConversationMessages($conversation->entityUrn, $query_params);

        if ($response['success']) {
            $response = Messages::invoke($response['data'], $conversation->entityUrn, $lastActivate);
        }


        if ($response['success'] && count($response['data'])) {
            $this->messageRepository->updateOrCreateCollection($response['data'], $conversation->id, $user->id, $account->id, $account->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT, true);
            sleep(1);
            $this->recursiveGetConversationMessages($user, $account, $conversation, [
                'createdBefore' => $response['lastActivityAt']
            ],$lastActivate);
        }
    }
}
