<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\KeyRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LinkedinSearchByKey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key;
    protected $account;

    /**
     * LinkedinSearchByKey constructor.
     * @param int $key_id
     * @param int $account_id
     */
    public function __construct(int $key_id, int $account_id)
    {
        $this->key = (new KeyRepository())->getById($key_id);
        $this->account = (new AccountRepository())->getById($account_id);

    }


    public function searchPeople($account, $key,int $start = 0)
    {
        $result = Response::profiles((array)Api::profile($account->login, $account->password)->searchPeople($key->name,$start));

        if ($result['success']) {
            (new ConnectionRepository())->updateOrCreateSelfAndConversationThoughCollection((array)$result['data'],$account->id,false,true,$key->id);
            $start+= 50;
            sleep(5);
            $this->searchPeople($account, $key,$start);
        }
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->searchPeople($this->account,$this->key,0);
    }
}
