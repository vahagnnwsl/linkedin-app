<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Company;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class ParseCompanies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $account_id)
    {
        $this->account = (new AccountRepository())->getById($account_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companies = (new  CompanyRepository())->getAll();
        $account = $this->account;

        $companies->map(function ($item) use ($account) {


            $resp = (new Company(Api::company($account->login, $account->password)->search($item->name)))();

            if ($resp['success']) {
                $item->update([
                    'entityUrn' => $resp['data']['entityUrn'],
                    'image' => $resp['data']['image'] ?? ''
                ]);
            }

            sleep(3);
        });
    }
}
