<?php


namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Constants;
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
    public function search(string $key_word,int $start = 0): array
    {
        $query_params = [
            'type' => 'COMPANY',
            'q' => 'type',
            'origin' => $start,
            'keywords' => $key_word
        ];

        return $this->client->setHeaders($this->login)->get(Constants::API_URL . '/typeahead/hitsV2', $query_params);
    }
}
