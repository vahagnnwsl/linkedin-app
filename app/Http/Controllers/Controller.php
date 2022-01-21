<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param bool $status
     * @param false $msg
     */
    public function putFlashMessage(bool $status, $msg = false)
    {
        if ($status) {
            session()->flash('success', $msg ?? 'Successfully');
        } else {
            session()->flash('error', $msg ??'Something went wrong');
        }

    }

    public function check($proxy): bool
    {
        if ($proxy->login && $proxy->password) {
            $config['proxy'] = "{$proxy->type}://{$proxy->login}:{$proxy->password}@{$proxy->ip}:{$proxy->port}";
        } else {
            $config['proxy'] = "{$proxy->type}://{$proxy->ip}:{$proxy->port}";
        }
        $client = new Client($config);

        try {
            $client->get("https://api.ipify.org?format=json");
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}
