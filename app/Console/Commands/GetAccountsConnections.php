<?php

namespace App\Console\Commands;


use App\Jobs\SearchByKey;

use App\Jobs\SyncAccountConnectionsJob;
use App\Repositories\AccountRepository;
use App\Repositories\KeyRepository;

use App\Repositories\ProxyRepository;
use Illuminate\Console\Command;

class GetAccountsConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetAccountsConnections';

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
            SyncAccountConnectionsJob::dispatch($account,$proxy);
        });

        return 1;
    }
}
