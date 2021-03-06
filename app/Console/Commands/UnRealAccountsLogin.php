<?php

namespace App\Console\Commands;


use App\Repositories\AccountRepository;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use App\Linkedin\Api;

class UnRealAccountsLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UnRealAccountsLogin';

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
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle(): int
    {

        (new AccountRepository())->getAllUnRealAccounts()->map(function ($account) {
            $proxy = $account->getRandomFirstProxy();
            Api::auth($account->login, $account->password, $proxy)->login();
        });

        return 1;
    }

}
