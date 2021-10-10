<?php

namespace App\Linkedin;

use App\Models\Account;
use App\Models\Log;
use App\Models\Proxy;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class Client
{
    /**
     * @var GuzzleClient
     */
    protected GuzzleClient $client;

    /**
     * @param Account $account
     * @param Proxy|null $proxy
     * @param string $header_type
     * @return $this
     */
    public function setHeaders(Account $account, Proxy $proxy = null, string $header_type = 'REQUEST_HEADERS'): self
    {

        $config = [];

//        if ($proxy) {
//            if ($proxy->login && $proxy->password) {
//                $config['proxy'] = "{$proxy->type}://{$proxy->login}:{$proxy->password}@{$proxy->ip}:{$proxy->port}";
//
//            } else {
//                $config['proxy'] = "{$proxy->type}://{$proxy->ip}:{$proxy->port}";
//
//            }
//        }

        $headers = [];

        $headers = Arr::add($headers, 'csrf-token', $account->jsessionid);
        $headers = Arr::add($headers, 'cookie', $account->cookie_web_str);

        foreach (Constants::$$header_type as $key => $val) {
            $headers = Arr::add($headers, $key, $val);
        }

        $config['headers'] = $headers;

        $this->client = new GuzzleClient($config);

        return $this;
    }

    /**
     * @param string $url
     * @param bool $parse_cookie
     * @param array $query_params
     * @return array
     */
    public function get(string $url, array $query_params = [], bool $is_file = false): array
    {
        if (!empty($query_params)) {
            $query_params = [
                'query' => $query_params
            ];
        }

        try {
            $response = $this->client->request('GET', $url, $query_params);

            return $this->workOnResponse($response, $is_file);

        } catch (GuzzleException $e) {


            Log::create([
                'status' => $e->getCode(),
                'msg' => $e->getMessage(),
                'request_url' => $url,
                'request_data' => json_encode($query_params),
            ]);

            return [
                'success' => false,
                'status' => $e->getCode(),
                'msg' => $e->getMessage()
            ];
        }
    }

    /**
     * @param string $url
     * @param array $payload
     * @param array $query_params
     * @param string $body_type
     * @return array
     * @throws GuzzleException
     */
    public function post(string $url, array $payload = [], array $query_params = [], string $body_type = 'json'): array
    {
        $options = [];

        if (!empty($payload)) {
            $options[$body_type] = $payload;
        }

//        if (!empty($query_params)) {
//            $options['params'] = $query_params;
//        }

        try {


            $response = $this->client->request('POST', $url, $options);

            return $this->workOnResponse($response);

        } catch (\Exception $e) {

            Log::create([
                'status' => $e->getCode(),
                'msg' => $e->getMessage(),
                'request_url' => $url,
                'request_data' => json_encode($options),
            ]);
            return [
                'success' => false,
                'status' => $e->getCode(),
                'msg' => $e->getMessage()
            ];
        }
    }


    /**
     * @param object $guzzle_response
     * @param bool $is_file
     * @return array
     */
    public function workOnResponse(object $guzzle_response, bool $is_file = false): array
    {

        $response['success'] = true;
        $response['status'] = $guzzle_response->getStatusCode();
        $response['cookies'] = Helper::parseCookies($guzzle_response->getHeader('Set-Cookie'));
        if ($is_file) {
            $response['data'] = $guzzle_response->getBody()->getContents();
        } else {
            $response['data'] = Helper::jsonDecode($guzzle_response->getBody()->getContents());
        }

        return $response;
    }

}
