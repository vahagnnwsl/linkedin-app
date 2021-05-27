<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Key;
use App\Models\Proxy;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Services\CompanyService;
use App\Services\ConnectionService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AccountsLogin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $type;
    protected $accountRepository;


    /**
     * AccountsLogin constructor.
     * @param int $type
     */
    public function __construct(int $type)
    {
        $this->type = $type;

        $this->accountRepository = new AccountRepository();

    }


    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle()
    {

        $accounts = $this->accountRepository->getByType($this->type);

        $accounts->map(function ($account) {

            $proxy = $account->getRandomFirstProxy();

            Api::auth($account->login, $account->password,$proxy)->login();

        });

    }
}
