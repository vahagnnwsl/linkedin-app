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

class SyncConversationsForOneAccount implements ShouldQueue
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
     * @param array $query_params
     */
    function getConversations($account, array $query_params = []): void
    {

        $resp = Response::conversationsConnections(Api::conversation($account->login, $account->password)->getConversations($query_params), $account->entityUrn);

        if ($resp['success']) {
            (new ConnectionRepository())->updateOrCreateSelfAndConversationThoughCollection($resp['data'], $account->id,true);
            $this->getConversations($account, ['createdBefore' => $resp['lastActivityAt']]);
        }

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->getConversations($this->account,[]);
    }
}
