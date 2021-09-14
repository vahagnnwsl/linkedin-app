<?php

namespace App\Linkedin\Responses;

use App\Linkedin\Constants;
use App\Linkedin\Helper;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\ArrayShape;


class Cookie
{

    protected static array $necessaryKeys = [
        'li_at',
        'chp_token',
        'liap',
        'lang',
        'JSESSIONID',
        'bcookie',
        'bscookie',
        'lidc',
    ];


    protected static array $necessarySocketKeys = [
        'lidc',
        'lissc',
        'lms_analytics',
        'lms_ads',
        'li_at',
        'li_sugr',
        'mbox',
        'bcookie',
        'timezone',
        'aam_uuid',
        'JSESSIONID',
        'liap',
        'bscookie',
        'lang',
        '_guid',
        'AnalyticsSyncHistory',
        'UserMatchHistory',
        'li_rm',
    ];

    /**
     * @param string $cookie
     * @return array
     */
    public static function parsCookieForWeb(string $cookie): array
    {
        $array = explode(';', $cookie);
        $cookie = Helper::parseCookies($array);
        return ['JSESSIONID' => str_replace("'", '', $cookie['JSESSIONID'])];
    }

    /**
     * @param string $cookie
     * @return array
     */
    #[ArrayShape(['str' => "string", 'crfToken' => "mixed"])]
    public static function parsCookieForSocket(string $cookie): array
    {
        $array = explode(';', $cookie);
        $cookie = Helper::parseCookies($array);
        return [
            'str' => Helper::cookieToString(collect($cookie)),
            'crfToken' => $cookie['JSESSIONID']
        ];
    }
}
