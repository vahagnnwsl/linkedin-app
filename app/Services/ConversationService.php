<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Messages;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;


class ConversationService
{


    /**
     * @var MessageRepository
     */
    protected MessageRepository $messageRepository;

    /**
     * @var ConversationRepository
     */
    protected ConversationRepository $conversationRepository;

    /**
     * @var ConnectionRepository
     */
    protected ConnectionRepository $connectionRepository;

    public function __construct()
    {
        $this->messageRepository = new MessageRepository();
        $this->conversationRepository = new ConversationRepository();
        $this->connectionRepository = new ConnectionRepository();
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
            $lastMessage = $conversation->messages()->orderBy('date', 'DESC')->first();
            if ($lastMessage && $lastMessage->date) {
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
     * @param null $lastActivate
     */
    public function recursiveGetConversationMessages(User $user, Account $account, Conversation $conversation, array $query_params, $lastActivate = null, $start = 0)
    {

        $response = Api::conversation($account)->getConversationMessages($conversation->entityUrn, $query_params);

        if ($response['success']) {
            $response = Messages::invoke($account,$response['data'], $conversation->entityUrn, $lastActivate);
        }


        if ($response['success'] && count($response['data'])) {
            $this->messageRepository->updateOrCreateCollection($response['data'], $conversation->id, $user->id, $account->id, $account->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT, true);
            sleep(1);
            $start++;
            $this->recursiveGetConversationMessages($user, $account, $conversation, [
                'createdBefore' => $response['lastActivityAt']
            ], $lastActivate, $start);
        }
    }


    /**
     * @param Account $account
     */
    public function getConversationLastEvents(Account $account): Collection
    {
        $conversations = $this->recursiveGetConversationLastEvents($account);

        $conversations = collect($conversations)->filter(function ($conversation) {
            $nativeConversation = $this->conversationRepository->getByEntityUrn($conversation['conversation']['entityUrn']);
            if (!$nativeConversation || ($nativeConversation && $nativeConversation->lastActivityAt < $conversation['conversation']['lastActivityAtToDate'])) {
                return true;
            }
            return false;
        });

        if (count($conversations)) {
            $entityUrns = $conversations->pluck('conversation.entityUrn')->toArray();
            $this->connectionRepository->updateOrCreateConversation(array_values($conversations->toArray()), $account->id);
            return $this->conversationRepository->model()::whereIn('entityUrn', $entityUrns)->get();
        }

        return collect([]);
    }

    /**
     * @param Account $account
     * @param array $conversations
     * @param array $params
     * @param int $start
     * @return array
     */
    public function recursiveGetConversationLastEvents(Account $account, array $conversations = [], array $params = [], int $start = 0)
    {

        $resp = Response::conversationsConnections(Api::conversation($account)->getConversations($params), $account->entityUrn);

        if ($resp['success']) {
            array_push($conversations, ...$resp['data']);
            $start++;
            return $this->recursiveGetConversationLastEvents($account, $conversations, ['createdBefore' => $resp['lastActivityAt']], $start);
        }
        return $conversations;
    }
}
