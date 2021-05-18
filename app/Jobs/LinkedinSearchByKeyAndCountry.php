<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\CountryRepository;
use App\Repositories\KeyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;

class LinkedinSearchByKeyAndCountry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key;
    protected $account;
    protected $country;

    /**
     * LinkedinSearchByKey constructor.
     * @param int $key_id
     * @param int $country_id
     * @param int $account_id
     */
    public function __construct(int $key_id, int $country_id, int $account_id)
    {
        $this->key = (new KeyRepository())->getById($key_id);
        $this->account = (new AccountRepository())->getById($account_id);
        $this->country = (new CountryRepository())->getById($country_id);

    }


    public function searchPeople($account, $key, $country, int $start = 0)
    {
        $result = (new Profile_2((array)Api::profile($account->login, $account->password)->searchPeople($key->name, $country->entityUrn, $start)))();


        if ($result['success']) {
            (new ConnectionRepository())->updateOrCreateSelfAndConversationThoughCollection((array)$result['data'], $account->id, false, true, $key->id);
//            File::put(storage_path('test/'.$start.'.json'),json_encode($result));
            $start += 10;
            sleep(5);
            $this->searchPeople($account, $key, $country, $start);
        }
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->searchPeople($this->account, $this->key, $this->country, 0);

        SyncCompaniesWithLinkedin::dispatch($this->account->id,$this->country->id,$this->key->id);

    }
}
