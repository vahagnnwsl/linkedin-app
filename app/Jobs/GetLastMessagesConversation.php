<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Messages;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetLastMessagesConversation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;
    protected $conversation;
    protected $user;
    protected $proxy;
    protected $messageRepository;

    /**
     * GetLastMessagesConversation constructor.
     * @param Account $account
     * @param Conversation $conversation
     * @param User $user
     */
    public function __construct(Account $account, Conversation $conversation, User $user)
    {
        $this->account = $account;
        $this->proxy = $account->getRandomFirstProxy();
        $this->conversation = $conversation;
        $this->user = $user;
        $this->messageRepository = new MessageRepository();
    }

    public function handle()
    {

        $response = (new Messages((array)Api::conversation($this->account->login, $this->account->password, $this->proxy)->getConversationMessages($this->conversation->entityUrn), $this->conversation->entityUrn))();

        if ($response['success']) {
            $this->messageRepository->updateOrCreateCollection($response['data']->toArray(), $this->conversation->id, $this->user->id, $this->account->id, $this->account->entityUrn, $this->messageRepository::SENDED_STATUS, $this->messageRepository::RECEIVE_EVENT,true);
        }
    }
}
