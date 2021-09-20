<?php

namespace App\Console\Commands\Pm2;

use App\Repositories\AccountRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PM2Configs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:set-pm2-ecosystem';

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
     * @return int
     */
    public function handle()
    {
        $apps = [
            'apps' => []
        ];


        $accounts = (new AccountRepository())->getAllRealAccounts();

        foreach ($accounts as $account) {

            array_push($apps['apps'],
                [
                    'name' => $account->login,
                    'script' => app_path('Linkedin/Node/index.js'),
                    'watch' => true,
                    'max_memory_restart' => '200M',
                    'env' => [
                        'COOKIE' => [
                            'str'=>$account->cookie_socket_str,
                            'crfToken'=>$account->jsessionid,
                        ],
                        'ACCOUNT_LOGIN' => $account->login,
                        'ACCOUNT_ID' => $account->id,
                        'APP_URL' => env('APP_URL')
                    ],
                ]
            );
        }

        File::put(storage_path('linkedin/ecosystem.json'), json_encode($apps));

        return 1;
    }
}
