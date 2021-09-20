<?php

namespace App\Jobs\Conversations;


use App\Models\Account;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetConversationsMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Account $account;
    protected User $user;

    /**
     * @param User $user
     * @param Account $account
     */
    public function __construct(User $user,Account $account)
    {
        $this->user = $user;
        $this->account = $account;
    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Account' => $this->account->full_name,
        ];
    }

    public function handle(): int
    {
        $this->account->conversations()->map(function ($conversation){
            GetConversationMessages::dispatch($this->user,$this->account,$conversation);
        });

        return 1;
    }
}
