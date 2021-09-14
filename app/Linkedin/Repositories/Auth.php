<?php

namespace App\Linkedin\Repositories;

use App\Linkedin\Client;
use App\Linkedin\Helper;
use App\Linkedin\Constants;
use Illuminate\Support\Facades\File;

class Auth extends Repository
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(): array
    {
        $anonymousAuthResponse = $this->client->setHeaders('','AUTH_HEADERS')->get(Constants::AUTH_URL);

        if ($anonymousAuthResponse['success']) {

            $response = $this->authenticateUser($this->login, $this->password, $anonymousAuthResponse['cookies']['JSESSIONID']);

            if ($response['success']) {

                if (!File::exists(base_path(Constants::SESSIONS_PATH))) {
                    File::makeDirectory(base_path(Constants::SESSIONS_PATH), $mode = 0777, true, true);
                }

                Helper::putJson($response['cookies'], Constants::SESSIONS_PATH . $this->login);
            }

            dump($response, $this->login);
            return $response;
        }

        return $anonymousAuthResponse;
    }


    /**
     * @param string $username
     * @param string $password
     * @param string $sessionId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authenticateUser(string $username, string $password, string $sessionId): array
    {
        return $this->client->setHeaders('','AUTH_HEADERS')->post(Constants::AUTH_URL, [
            "session_key" => $username,
            "session_password" => $password,
            "JSESSIONID" => $sessionId
        ],[],'form_params');
    }
}
