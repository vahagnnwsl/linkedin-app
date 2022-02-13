<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Constants;
use Illuminate\Support\Arr;

class Connection extends Repository
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
     * @param string $url
     * @return array
     */
    public function getFile(string $url): array
    {
        return $this->client->setHeaders($this->account)->get($url, [], true);
    }

}
