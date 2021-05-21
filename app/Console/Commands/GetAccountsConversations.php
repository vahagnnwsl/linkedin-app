<?php

namespace App\Console\Commands;


use App\Jobs\GetAccountConversations;
use App\Jobs\SearchByKey;

use App\Jobs\SyncAccountConnectionsJob;
use App\Jobs\SyncAccountConversations;
use App\Repositories\AccountRepository;
use App\Repositories\KeyRepository;

use App\Repositories\ProxyRepository;
use Illuminate\Console\Command;

class GetAccountsConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetAccountsConversations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $accountsRepository;

    protected $proxyRepository;

    public function __construct()
    {
        $this->accountsRepository = new AccountRepository();
        $this->proxyRepository = new ProxyRepository();

        parent::__construct();
    }

    /**
     * @return int
     */
    public function handle()
    {
        $accounts = $this->accountsRepository->getAll();

        $accounts->map(function ($account) {
            $proxy = $this->proxyRepository->inRandomOrderFirst();
            GetAccountConversations::dispatch($account,$proxy);
        });

        return 1;
    }
}
