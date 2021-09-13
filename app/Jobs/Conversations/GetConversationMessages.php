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

    protected Account $account;
    protected Conversation $conversation;
    protected ConversationService $conversationService;
    protected User $user;
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
        $this->conversationService->getConversationMessages($this->user,$this->account,$this->conversation);
    }
}
