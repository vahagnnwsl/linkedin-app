<?php

namespace App\Jobs\Account;

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

class GetConnections implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Account
     */
    protected $account;

    /**
     * @var Proxy
     */
    protected  $proxy;

    protected $connectionService;

    /**
     * SyncAccountConnectionsJob constructor.
     * @param Account $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;

        $this->proxy = $account->proxy;

        $this->connectionService = new ConnectionService();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->connectionService->getAccountConnections($this->account, $this->proxy);
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
}
