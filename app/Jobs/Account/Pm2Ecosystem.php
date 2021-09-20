<?php

namespace App\Jobs\Account;


use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Proxy;
use App\Repositories\AccountRepository;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class Pm2Ecosystem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;




    protected AccountRepository $accountRepository;



    public function __construct()
    {
        $this->accountRepository = new AccountRepository();

    }

    /**
     * @return array
     */
    public function displayAttribute(): array
    {
        return [
            'JobClass' => get_class($this),
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $apps = [
            'apps' => []
        ];


        $accounts = $this->accountRepository->getAllRealAccounts();

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
        //TO DO CHANGE
        shell_exec('npm run pm2-stop-linkedin');
        shell_exec('npm run pm2-start-linkedin');

    }
}
