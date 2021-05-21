<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Proxy;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetAccountConversations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var Proxy
     */
    protected $proxy;


    protected $connectionService;


    /**
     * SyncAccountConversations constructor.
     * @param Account $account
     * @param Proxy $proxy
     */
    public function __construct(Account $account, Proxy $proxy)
    {
        $this->account = $account;
        $this->proxy = $proxy;
        $this->connectionService = new ConnectionService();

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->connectionService->getAccountConversations($this->account, $this->proxy);
    }
}
