<?php

namespace App\Jobs;

use App\Linkedin\Api;
use App\Linkedin\Responses\Response;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncConnectionsForOneAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $key;
    protected $account;

    /**
     * LinkedinSearchByKey constructor.
     * @param int $account_id
     */
    public function __construct( int $account_id)
    {
        $this->account = (new AccountRepository())->getById($account_id);

    }


    /**
     * @param $account
     * @param int $start
     */
    function getConversations($account, int $start = 0): void
    {
        $result = Response::connections(Api::profile($account->login, $account->password)->getProfileConnections($start));

        if ($result['success']) {

            (new ConnectionRepository())->updateOrCreateSelfAndConversationThoughCollection((array)$result['data'],$account->id);
            $start += 50;
            $this->getConversations($account, $start);
        }

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->getConversations($this->account, 0);

    }
}
