<?php

namespace App\Console\Commands;


use App\Jobs\GetAccountConversations;
use App\Jobs\GetConversationMessages;
use App\Jobs\GetLastMessagesConversation;
use App\Jobs\SearchByKey;

use App\Jobs\SyncAccountConnectionsJob;
use App\Jobs\SyncAccountConversations;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\KeyRepository;

use App\Repositories\ProxyRepository;
use Illuminate\Console\Command;

class GetUnRealAccountConversationMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetUnRealAccountConversationMessages';

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
        $accounts = $this->accountsRepository->getAllUnRealAccounts();

        $accounts->map(function ($account) {
            $conversations = $account->conversations;
            $conversations->map(function ($conversation) use ($account) {
                GetConversationMessages::dispatch(User::first(),$account, $conversation );
            });
        });

        return 1;
    }
}
