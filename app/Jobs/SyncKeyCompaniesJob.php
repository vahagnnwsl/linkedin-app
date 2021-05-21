<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Company;
use App\Linkedin\Responses\Profile_2;
use App\Models\Account;
use App\Models\Key;
use App\Repositories\AccountRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\KeyRepository;
use App\Services\CompanyService;
use App\Services\ConnectionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class SyncKeyCompaniesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;

    protected $companyRepository;

    protected $companyService;

    protected $connectionService;


    /**
     * @var Key
     */
    protected $key;

    /**
     * RunKeyCompaniesJob constructor.
     * @param Key $key
     */
    public function __construct(Key $key)
    {
        $this->key = $key;

        $this->companyRepository = new CompanyRepository();

        $this->companyService = new CompanyService();

        $this->connectionService = new ConnectionService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $noParsedCompanies = $this->key->noParsedCompanies;

        $noParsedCompanies->map(function ($company)  {

            $account = $this->key->getRandomRelation('accounts');

            $proxy = $this->key->getRandomRelation('proxies');

            $this->companyService->getInfoFormLinkedinAndUpdate($company, $account, $proxy);

            sleep(3);

        });


        $parsedCompanies = $this->key->parsedCompanies;

        $parsedCompanies->map(function ($company)  {

            $this->connectionService->search($this->key, [
                'companyEntityUrn' => $company->entityUrn,

            ]);

            sleep(3);
        });

    }
}



