<?php

namespace App\Console\Commands;


use App\Http\Repositories\LinkedinConversationRepository;
use App\Http\Repositories\LinkedinMessageRepository;
use App\Http\Repositories\UserRepository;
use App\Linkedin\Helper;
use App\Linkedin\Responses\Response;
use App\Models\AaccountsConversationsLimit;
use App\Repositories\AccountRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Linkedin\Api;
use Illuminate\Support\Facades\File;

class PM2Configs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:PM2Configs';

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
                        'COOKIE' => json_decode(File::get(storage_path('linkedin/cookies/' . $account->login . '.json'))),
                        'ACCOUNT_LOGIN' => $account->login,
                        'ACCOUNT_ID' => $account->id,
                        'APP_URL' => env('APP_URL')
                    ],
                ]
            );
        }

       File::put(storage_path('linkedin/Node/ecosystem.json'),json_encode($apps));

    }
}
