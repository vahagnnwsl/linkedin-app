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
     * @param string $key
     * @param string $country_id
     * @param string|null $company_id
     * @param int $start
     * @return array
     */
    public function searchPeople(string $key,  string $country_id ,string $company_id = null,int $start = 0): array
    {
//        $query_params = [
//            'count' => 50,
//            'filters' => [
//                'resultType->PEOPLE',
//                'geoUrn->'.$country_id,
//            ],
//            'origin' => 'GLOBAL_SEARCH_HEADER',
//            "queryContext" => "List(spellCorrectionEnabled->true,relatedSearchesEnabled->true,kcardTypes->PROFILE)",
//            'q' => 'all',
//            'start' => $start,
//            'keywords' => $key_word
//        ];

//        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/search/blended', $query_params);

        $companyStr ="";

        if ($company_id){
            $companyStr = "currentCompany:List(" . $company_id . "),";
        }


        $query = "decorationId=com.linkedin.voyager.dash.deco.search.SearchClusterCollection-92&origin=FACETED_SEARCH&q=all&query=(keywords:" . $key . ",flagshipSearchIntent:SEARCH_SRP,queryParameters:(".$companyStr."resultType:List(PEOPLE),geoUrn:List(" . $country_id . ")),includeFiltersInResponse:false)&start=" . $start;

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/search/dash/clusters?' . $query);
    }


    /**
     * @param int $country_id
     * @param int $company_id
     * @param string $key
     * @param int $start
     * @return array
     */
    public function searchPeopleByCompanyIdAndKeyAndCountry(int $country_id, int $company_id, string $key, int $start = 0): array
    {

        $query = "decorationId=com.linkedin.voyager.dash.deco.search.SearchClusterCollection-92&origin=FACETED_SEARCH&q=all&query=(keywords:" . $key . ",flagshipSearchIntent:SEARCH_SRP,queryParameters:(currentCompany:List(" . $company_id . "),resultType:List(PEOPLE),geoUrn:List(" . $country_id . ")),includeFiltersInResponse:false)&start=" . $start;

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/search/dash/clusters?' . $query);

    }

}
