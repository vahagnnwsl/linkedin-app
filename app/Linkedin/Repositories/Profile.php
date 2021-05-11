<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Constants;
use Illuminate\Support\Arr;

class Profile extends Repository
{

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Repository constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return array
     */
    public function getOwnProfile(): array
    {
        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/me');
    }

    /**
     * @param int $start
     * @return array
     */
    public function getProfileConnections(int $start = 0): array
    {

        $query_params = [
            'decorationId' => 'com.linkedin.voyager.dash.deco.web.mynetwork.ConnectionListWithProfile-5',
            'count' => 50,
            'q' => 'search',
            'sortType' => 'RECENTLY_ADDED',
            'start' => $start,
        ];

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/relationships/dash/connections', $query_params);
    }

    /**
     * @param string $public_identifier
     * @return array
     */
    public function getProfile(string $public_identifier): array
    {
        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/identity/profiles/' . $public_identifier . '/profileView');
    }

    /**
     * @param string $key_word
     * @param int $start
     * @param int|null $company_id
     * @return array
     */
    public function searchPeople(string $key_word, int $start = 0, int $company_id = null): array
    {
        $query_params = [
            'count' => 49,
            'filters' => [
                'resultType->PEOPLE',
                'geoUrn->103030111',
                'currentCompany->3189499',
            ],
            'origin' => 'GLOBAL_SEARCH_HEADER',
            "queryContext" => "List(spellCorrectionEnabled->true,relatedSearchesEnabled->true,kcardTypes->PROFILE)",
            'q' => 'all',
            'start' => $start,
            'keywords' => $key_word
        ];

//        if ($company_id){
//            array_push($query_params['filters'],'currentCompany->'.$company_id);
//        }


        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/search/blended', $query_params);
    }

    /**
     * @param int|null $company_id
     * @param string|null $key
     * @param int $start
     * @return array
     */
    public function searchPeopleByCompanyIdAndKey(int $company_id = null, string $key = null, $start = 0): array
    {

        $query = "decorationId=com.linkedin.voyager.dash.deco.search.SearchClusterCollection-92&origin=FACETED_SEARCH&q=all&query=(keywords:".$key.",flagshipSearchIntent:SEARCH_SRP,queryParameters:(currentCompany:List(" . $company_id . "),resultType:List(PEOPLE)),includeFiltersInResponse:false)&start=" . $start;

//        $query_params = [
//            'decorationId' => 'com.linkedin.voyager.dash.deco.search.SearchClusterCollection-97',
//            'origin' => 'FACETED_SEARCH',
//            'q' => 'all',
//            "query" => "(keywords:".$key.",flagshipSearchIntent:SEARCH_SRP,queryParameters:(currentCompany:List(" . $company_id . "),resultType:List(PEOPLE)),includeFiltersInResponse:false)",
//            'start' => $start
//        ];

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/search/dash/clusters?' . $query);

    }

}
