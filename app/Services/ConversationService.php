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
     * @param bool $isLast
     */
    public function getConversationMessages(User $user, Account $account, Conversation $conversation, bool $isLast = false)
    {
        $query_params = [];
        if ($isLast) {
            $query_params['createdBefore'] = time() * 1000;
        }
        $this->recursiveGetConversationMessages($user, $account, $conversation, $query_params);
    }

    /**
     * @param User $user
     * @param Account $account
     * @param Conversation $conversation
     * @param array $query_params
     * @param $proxy
     */
    public function recursiveGetConversationMessages(User $user, Account $account, Conversation $conversation, array $query_params)
    {

        $response = Api::conversation($account, $account->proxy)->getConversationMessages($conversation->entityUrn, $query_params);


        if ($response['success']) {
            $response = Messages::invoke($response['data'], $conversation->entityUrn);
        }

        if ($response['success'] && count($response['data'])) {
            $this->messageRepository->updateOrCreateCollection($response['data'], $conversation->id, $user->id, $account->id, $account->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT, true);
            sleep(1);
            $this->recursiveGetConversationMessages($user, $account, $conversation, [
                'createdBefore' => $response['lastActivityAt']
            ]);
        }
    }
}
