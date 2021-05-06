<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncLastMessagesForOneAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;

    protected $conversation;
    protected $user_id;

    /**
     * LinkedinSearchByKey constructor.
     * @param int $account_id
     * @param int $conversation_id
     * @param int $user_id
     */
    public function __construct(int $account_id, int $conversation_id, int $user_id)
    {
        $this->account = (new AccountRepository())->getById($account_id);
        $this->conversation = (new ConversationRepository())->getById($conversation_id);
        $this->user_id = $user_id;
    }

    public function handle()
    {

        $response = Response::messages((array)Api::conversation($this->account->login, $this->account->password)->getConversationMessages($this->conversation->entityUrn), $this->conversation->entityUrn);

        if ($response['success']) {
            (new MessageRepository())->updateOrCreateCollection($response['data']->toArray(), $this->conversation->id, $this->user_id, $this->account->id, $this->account->entityUrn, MessageRepository::SENDED_STATUS, MessageRepository::RECEIVE_EVENT);
        }
    }
}
