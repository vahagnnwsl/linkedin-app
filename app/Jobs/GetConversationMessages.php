<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConversationRepository;
use App\Repositories\MessageRepository;
use App\Services\ConversationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetConversationMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;
    protected $conversation;
    protected $conversationService;
    protected $user;
    protected $proxy;
    protected $messageRepository;

    /**
     * GetConversationMessages constructor.
     * @param User $user
     * @param Account $account
     * @param Conversation $conversation
     */
    public function __construct(User $user,Account $account,Conversation $conversation)
    {
        $this->user = $user;
        $this->account = $account;
        $this->conversation = $conversation;
        $this->conversationService = new ConversationService;
    }

    public function handle()
    {

        $this->conversationService->getConversationMessages($this->user,$this->account,$this->conversation);

    }
}
