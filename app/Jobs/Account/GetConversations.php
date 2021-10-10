<?php

namespace App\Jobs\Account;


use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Proxy;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetConversations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    protected Account $account;

    /**
     * @var Proxy
     */
    protected $proxy ;


    protected ConnectionService $connectionService;


    /**
     * GetAccountConversations constructor.
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
        $this->proxy = $account->proxy;
        $this->connectionService = new ConnectionService();

    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
            'Account' => $this->account->full_name ,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->connectionService->getAccountConversations($this->account);
    }
}
