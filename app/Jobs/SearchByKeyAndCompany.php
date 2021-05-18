<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SearchByKeyAndCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key;

    protected $company;

    protected $country;

    protected $account;

    /**
     * SearchByKeyAndCompany constructor.
     * @param int $account_id
     * @param int $country_id
     * @param int $company_id
     * @param int $key_id
     */
    public function __construct(int $account_id, int $country_id, int $company_id, int $key_id)
    {
        $this->key = (new KeyRepository())->getById($key_id);
        $this->country = (new CountryRepository())->getById($country_id);
        $this->company = (new CompanyRepository())->getById($company_id);
        $this->account = (new AccountRepository())->getById($account_id);

    }


    public function searchPeople($account, $country, $company, $key, int $start = 0)
    {

        $result = (new Profile_2((array)Api::profile($account->login, $account->password)->searchPeopleByCompanyIdAndKey($country->entityUrn, $company->entityUrn, $key->name, $start)))();

        if ($result['success']) {
            (new ConnectionRepository())->updateOrCreateSelfAndConversationThoughCollection((array)$result['data'], $account->id, false, true, $key->id,true);
            $start += 10;
            sleep(5);
            $this->searchPeople($account, $country, $company, $key, $start);


        }
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->searchPeople($this->account, $this->country, $this->company, $this->key, 0);
    }
}
