<?php

namespace App\Linkedin;

class Constants
{
    const SUCCESS_STATUS = 200;
    const UNKNOWN_ERROR_MESSAGE = 'Unknown error';
    const AUTH_URL = 'https://www.linkedin.com/uas/authenticate';
    const API_URL = 'https://www.linkedin.com/voyager/api';
    const SESSIONS_PATH = '/storage/linkedin/sessions/';
    const DEFAULT_AVATAR = '/dist/img/lin_def_image.svg';
    const REQUEST_HEADERS_TYPE = 'REQUEST_HEADERS';
    const UNTIL_DISABLED_DAY = 10;

    /**
     * @var array|string[]
     */
    public static array $AUTH_HEADERS = [
        "X-Li-User-Agent" => "LIAuthLibrary:3.2.4 \
                            com.linkedin.LinkedIn:8.8.1 \
                            iPhone:8.3",
        "User-Agent" => "LinkedIn/8.8.1 CFNetwork/711.3.18 Darwin/14.0.0",
        "X-User-Language" => "en",
        "X-User-Locale" => "en_US",
        "Accept-Language" => "en-us"
    ];
    /**
     * @var array|string[]
     */
    public static array $REQUEST_HEADERS = [
        'authority' => 'www.linkedin.com',
        'x-restli-protocol-version' => '2.0.0',
        'x-li-lang' => 'en_US',
        'user-agent' =>
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36',
        'x-li-page-instance' => 'urn:li:page:d_flagship3_feed;N2rwHUFIQrykkt4exT64CQ==',
        'accept' => 'application/vnd.linkedin.normalized+json+2.1',
        'x-li-track' => '{"clientVersion":"1.5.*","osName":"web","timezoneOffset":2,"deviceFormFactor":"DESKTOP","mpName":"voyager-web"}',
        'sec-fetch-site' => 'same-origin',
        'sec-fetch-mode' => 'cors',
        'referer' => 'https://www.linkedin.com/feed/?trk=guest_homepage-basic_nav-header-signin',
        'accept-encoding' => 'gzip, deflate, br',
        'accept-language' => 'en-US,en;q=0.9'
    ];

}
