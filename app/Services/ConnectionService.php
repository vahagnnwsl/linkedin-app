<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Invitation;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Country;
use App\Models\Key;
use App\Models\Proxy;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConnectionRequestRepository;
use Illuminate\Support\Facades\File;

class ConnectionService
{


    protected ConnectionRepository $connectionRepository;
    protected ConnectionRequestRepository $connectionRequestRepository;
    protected AccountRepository $accountRepository;

    public function __construct()
    {
        $this->connectionRepository = new ConnectionRepository();
        $this->connectionRequestRepository = new ConnectionRequestRepository();
        $this->accountRepository = new AccountRepository();
    }

    /**
     * @param Key $key
     * @param array $params
     */
    public function search(Key $key, array $params)
    {

        $account = $key->getRandomRelation('accounts');
        $country = $key->country;

        $this->recursiveSearch($key, $account, $country, $params, 0);
    }

    /**
     * @param Account $account
     * @param Proxy $proxy
     */
    public function getAccountConnections(Account $account, Proxy $proxy = null)
    {

        $this->recursiveGetAccountConnections($account, $proxy, 0);
    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     */
    public function getAccountConversations(Account $account, Proxy $proxy = null)
    {

        $this->recursiveGetAccountConversations($account, [], $proxy,);
    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @param int $start
     */
    public function recursiveGetAccountConnections(Account $account, Proxy $proxy = null, int $start = 0)
    {
        $result = Response::connections(Api::profile($account, $proxy)->getProfileConnections($start));

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateConnections((array)$result['data'], $account->id);
            $start += 50;
            sleep(5);
            $this->recursiveGetAccountConnections($account, $proxy, $start);
        }

    }


    /**
     * @param Key $key
     * @param Account $account
     * @param Country $country
     * @param array $params
     * @param int $start
     */
    public function recursiveSearch(Key $key, Account $account, Country $country, array $params = [], $start = 0)
    {

        $proxy = $account->proxy;

        $result = (new Profile_2(Api::profile($account, $proxy)->searchPeople($key->name, $country->entityUrn, $params['companyEntityUrn'] ?? null, $start)))();

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateConnectionsOnTimeKeySearch((array)$result['data'], $account->id, $key->id);
            $start += 10;
            sleep($params['sleep'] ?? 2);

            $this->recursiveSearch($key, $account, $country, $params, $start);
        }

    }

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @param array $params
     */
    public function recursiveGetAccountConversations(Account $account, array $params, Proxy $proxy = null)
    {

        $resp = Response::conversationsConnections(Api::conversation($account, $proxy)->getConversations($params), $account->entityUrn);

        if ($resp['success']) {
            $this->connectionRepository->updateOrCreateConversation($resp['data'], $account->id);
            $this->recursiveGetAccountConversations($account, ['createdBefore' => $resp['lastActivityAt']], $proxy);
        }

    }


    /**
     * @param Account $account
     */
    public function getAccountRequest(Account $account)
    {
        $proxy = $account->getRandomFirstProxy();

        $resp = (new Invitation(Api::invitation($account, $proxy)->getSentInvitations()))();

        if ($resp['success']) {

            $ides = [];

            foreach ($resp['data'] as $item) {

                $connection = $this->connectionRepository->updateOrCreate(['entityUrn' => $item['connection']['entityUrn']], $item['connection']);

                $request = $this->connectionRequestRepository->updateOrCreate([
                    'account_id' => $account->id,
                    'connection_id' => $connection->id,
                ], [
                    'account_id' => $account->id,
                    'connection_id' => $connection->id,
                    'status' => $this->connectionRequestRepository::$PENDING_STATUS,
                    'message' => $item['message'],
                    'created_at' => $item['created_at']
                ]);

                array_push($ides, $request->id);
            }

            $connection_ides = $this->connectionRequestRepository->updateCollectionStatusAndReturnRecordsConnectionIdes($account->id, $ides);

            if (count($connection_ides)) {
                foreach ($connection_ides as $connection_id) {
                    $this->accountRepository->attachConnection($account->id, $connection_id);

                }
            }
        }
    }
}
