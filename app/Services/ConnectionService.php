<?php

namespace App\Services;


use App\Linkedin\Api;
use App\Linkedin\Responses\Profile_2;
use App\Linkedin\Responses\Response;
use App\Models\Account;
use App\Models\Country;
use App\Models\Key;
use App\Models\Proxy;
use App\Repositories\ConnectionRepository;
use App\Repositories\ProxyRepository;

class ConnectionService
{


    protected $connectionRepository;
    protected $proxyRepository;

    public function __construct()
    {
        $this->connectionRepository = new ConnectionRepository();
    }


    public function search(Key $key, array $params)
    {

        $account = $key->getRandomRelation('accounts');
        $proxy = $key->getRandomRelation('proxies');
        $country = $key->country;

        $this->recursiveSearch($key, $proxy, $account, $country, $params, 0);
    }

    public function getAccountConnections(Account $account,Proxy $proxy)
    {

        $this->recursiveGetAccountConnections($account,$proxy,0);
    }

    public function getAccountConversations(Account $account, Proxy $proxy)
    {

        $this->recursiveGetAccountConversations($account, $proxy, []);

    }
    public function recursiveGetAccountConnections(Account $account, Proxy $proxy, int $start = 0)
    {
        $result = Response::connections(Api::profile($account->login, $account->password, $proxy)->getProfileConnections($start));

        if ($result['success']) {
            $this->connectionRepository->updateOrCreateSelThoughCollection((array)$result['data'], $account->id,0,true,false,false);
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
            sleep($params['sleep'] ?? 5);

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
}
