<?php


namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Constants;
use App\Models\Proxy;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;


class Company extends Repository
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
     * @param string $key_word
     * @param int $start
     * @return array
     */
    public function search(string $keyWord, int $start = 0): array
    {
        $query_params = [
            'type' => 'COMPANY',
            'q' => 'type',
            'origin' => $start,
            'keywords' => $keyWord
        ];

        return $this->client->setHeaders($this->login,Constants::REQUEST_HEADERS_TYPE,$this->proxy)->get(Constants::API_URL . '/typeahead/hitsV2', $query_params);
    }

//    /**
//     * @param string $country_id
//     * @param int $start
//     * @return array
//     */
//    public function get(string $country_id, int $start = 0): array
//    {
//        $query = "decorationId=com.linkedin.voyager.dash.deco.search.SearchClusterCollection-103&origin=FACETED_SEARCH&q=all&query=(flagshipSearchIntent:SEARCH_SRP,queryParameters:(industry:List(96,4,68,80),companyHqGeo:List(" . $country_id . "),resultType:List(COMPANIES)),includeFiltersInResponse:false)&start=" . $start;
//
//        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/search/dash/clusters?' . $query);
//    }

}
