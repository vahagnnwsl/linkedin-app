<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Company;
use App\Models\Account;
use App\Repositories\AccountRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\KeyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class SyncCompaniesWithLinkedin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $account;

    protected $companyRepository;
    protected $country_id;
    protected $key;
    protected $key_id;

    /**
     * SyncCompaniesWithLinkedin constructor.
     * @param int $account_id
     * @param int $country_id
     * @param int $key_id
     */
    public function __construct(int $account_id, int $country_id, int $key_id)
    {
        $this->account = (new AccountRepository())->getById($account_id);
        $this->key = (new KeyRepository())->getById($key_id);
        $this->country_id = $country_id;
        $this->companyRepository = new  CompanyRepository();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $companies = $this->key->noParsedCompanies;

        $account = $this->account;

        $companies->map(function ($item) use ($account) {

            $resp = (new Company(Api::company($account->login, $account->password)->search($item->name)))();

            if ($resp['success']) {

                $data = $resp['data'][0];

                $data['is_parsed'] =    $this->companyRepository::$PARSED_SUCCESS_STATUS;

            } else {

                $data['is_parsed'] = $this->companyRepository::$PARSED_FAILED_STATUS;
            }

            $this->companyRepository->update($item->id, $data);

            sleep(3);
        });

        $parsedCompanies =  $this->key->parsedCompanies;

        $parsedCompanies->map(function ($item)  {
            LinkedinSearchByKeyAndCountryAndCompany::dispatch($this->account->id, $this->country_id , $item->id, $this->key->id);
            sleep(5);
        });


    }
}
