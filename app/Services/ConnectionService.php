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
use App\Repositories\ProxyRepository;

class ConnectionService
{


    protected $connectionRepository;
    protected $connectionRequestRepository;
    protected $proxyRepository;
    protected $accountRepository;

    public function __construct()
    {
        $this->connectionRepository = new ConnectionRepository();
        $this->connectionRequestRepository = new ConnectionRequestRepository();
        $this->accountRepository = new AccountRepository();
    }

    public function search(Key $key, array $params)
    {

        $account = $key->getRandomRelation('accounts');
        $proxy = $account->getRandomFirstProxy();
        $country = $key->country;

        $this->recursiveSearch($key, $proxy, $account, $country, $params, 0);
    }

    public function getAccountConnections(Account $account, Proxy $proxy)
    {

        $this->recursiveGetAccountConnections($account, $proxy, 0);
    }

    public function getAccountConversations(Account $account, Proxy $proxy)
    {

        $this->recursiveGetAccountConversations($account, $proxy, []);

    }

    public function recursiveGetAccountConnections(Account $account, Proxy $proxy, int $start = 0)
    {
        $result = Response::connections(Api::profile($account->login, $account->password, $proxy)->getProfileConnections($start));

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateSelThoughCollection((array)$result['data'], $account->id, 0, true, false, false);
            $start += 50;
            sleep(5);

            $this->recursiveGetAccountConnections($account, $proxy, $start);
        }

    }

    public function recursiveSearch(Key $key, Proxy $proxy, Account $account, Country $country, array $params = [], $start = 0)
    {

        $result = (new Profile_2(Api::profile($account->login, $account->password, $proxy)->searchPeople($key->name, $country->entityUrn, $params['companyEntityUrn'] ?? null, $start)))();

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateSelThoughCollection(
                (array)$result['data'],
                $account->id,
                $key->id,
                $params['conDistance'] ?? false,
                $params['conCompany'] ?? false,
                $params['conConversation'] ?? false
            );
            $start += 10;
            sleep($params['sleep'] ?? 2);

            $this->recursiveSearch($key, $proxy, $account, $country, $params, $start);
        }

    }

    public function recursiveGetAccountConversations(Account $account, Proxy $proxy, array $params)
    {

        $resp = Response::conversationsConnections(Api::conversation($account->login, $account->password, $proxy)->getConversations($params), $account->entityUrn);

        if ($resp['success']) {
            $this->connectionRepository->updateOrCreateSelThoughCollection($resp['data'], $account->id, 0, true, false, true);
            $this->recursiveGetAccountConversations($account, $proxy, ['createdBefore' => $resp['lastActivityAt']]);
        }

    }


    /**
     * @param Account $account
     */
    public function getAccountRequest(Account $account)
    {
        $proxy = $account->getRandomFirstProxy();

        $resp = (new Invitation(Api::invitation($account->login, $account->password, $proxy)->getSentInvitations()))();

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
