<?php

namespace App\Jobs\Conversations;


use App\Models\Account;
use App\Models\Conversation;
use App\Models\User;
use App\Services\ConversationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetConversationMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    protected Account $account;

    /**
     * @var Conversation
     */
    protected Conversation $conversation;

    /**
     * @var ConversationService
     */
    protected ConversationService $conversationService;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var bool
     */
    protected bool $isLast;

    /**
     * GetConversationMessages constructor.
     * @param User $user
     * @param Account $account
     * @param Conversation $conversation
     * @param bool $isLast
     */
    public function __construct(User $user,Account $account,Conversation $conversation,bool $isLast = false)
    {
        $this->user = $user;
        $this->account = $account;
        $this->conversation = $conversation;
        $this->conversationService = new ConversationService;
        $this->isLast = $isLast;
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Account' => $this->account->full_name,
            'Conversation' => 'ID '.$this->conversation->id.' | EntityUrn: '.$this->conversation->entityUrn,
        ];
    }

    public function handle()
    {
        $this->conversationService->getConversationMessages($this->user,$this->account,$this->conversation,$this->isLast);
    }


}
