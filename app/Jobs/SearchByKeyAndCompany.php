<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\ConnectionRepository;
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
    protected $account;

    /**
     * LinkedinSearchByKey constructor.
     * @param int $key_id
     * @param int $account_id
     */
    public function __construct(int $key_id,int $company_id, int $account_id)
    {
        $this->key = (new KeyRepository())->getById($key_id);
        $this->account = (new AccountRepository())->getById($account_id);
        $this->company = (new CompanyRepository())->getById($company_id);
    }


    public function searchPeople($account, $key,$company,int $start = 0)
    {

        $result = (new Profile_2((array)Api::profile($account->login, $account->password)->searchPeopleByCompanyIdAndKey( $company->entityUrn,$key->name,$start)))();

        if ($result['success']) {
            (new ConnectionRepository())->updateOrCreateSelfAndConversationThoughCollection((array)$result['data'],$account->id,false,true,$key->id);
            $start+= 10;
            sleep(5);
            $this->searchPeople($account, $key,$company,$start);
        }
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->searchPeople($this->account,$this->key,$this->company,0);
    }
}
