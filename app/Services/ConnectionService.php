<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Invitation;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Country;
use App\Models\Key;
use App\Repositories\AccountRepository;
use App\Repositories\ConnectionRepository;
use App\Repositories\ConnectionRequestRepository;

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
    public function search(Key $key, Account $account, array $params)
    {
        $country = $key->country;
        $this->recursiveSearch($key, $account, $country, $params, 0);
    }

    /**
     * @param Account $account
     */
    public function getAccountConnections(Account $account)
    {

        $this->recursiveGetAccountConnections($account, 0);
    }

    /**
     * @param Account $account
     */
    public function getAccountConversations(Account $account)
    {

        $this->recursiveGetAccountConversations($account, []);
    }

    /**
     * @param Account $account
     * @param int $start
     */
    public function recursiveGetAccountConnections(Account $account, int $start = 0)
    {
        $result = Response::connections(Api::profile($account)->getProfileConnections($start));

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateConnections((array)$result['data'], $account->id);
            $start += 50;
            sleep(5);
            $this->recursiveGetAccountConnections($account, $start);
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

        $result = (new Profile_2(Api::profile($account)->searchPeople($key->name, $country->entityUrn, $params['companyEntityUrn'] ?? null, $start)))();

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateConnectionsOnTimeKeySearch((array)$result['data'], $account->id, $key->id);
            $start += 10;
            sleep($params['sleep'] ?? 2);

            $this->recursiveSearch($key, $account, $country, $params, $start);
        }

    }

    /**
     * @param Account $account
     * @param array $params
     */
    public function recursiveGetAccountConversations(Account $account, array $params)
    {

        $resp = Response::conversationsConnections(Api::conversation($account)->getConversations($params), $account->entityUrn);

        if ($resp['success']) {
            $this->connectionRepository->updateOrCreateConversation($resp['data'], $account->id);
            $this->recursiveGetAccountConversations($account, ['createdBefore' => $resp['lastActivityAt']]);
        }

    }


    /**
     * @param Account $account
     */
    public function getAccountRequest(Account $account)
    {

        $resp = Invitation::invoke(Api::invitation($account)->getSentInvitations());

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
                    'date' => $item['created_at']
                ]);

                array_push($ides, $request->id);
            }

            $this->connectionRequestRepository->deleteAccepted($ides, $account->id);
        }
    }
}
