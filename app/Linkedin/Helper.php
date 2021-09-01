<?php

namespace App\Linkedin;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use function _\pickBy;

class Helper
{
    /**
     * @param array $cookies
     * @return array
     */
    public static function parseCookies(array $cookies): array
    {

        return collect($cookies)->reduce(function ($array, $item) {

            $item = str_replace('"', '', $item);

            parse_str(strtr($item, array('&' => '%26', '+' => '%2B', ';' => '&')), $cookies);

            $filtered = Arr::where($cookies, function ($value, $key) {
                return $value !== '';
            });

            $keys = array_keys($filtered);

            $arr = pickBy((object)$filtered, function ($v, $k) use ($keys) {
                return $k === $keys[0];
            });


            return array_merge($array, (array)$arr);

        }, []);
    }

    /**
     * @param object $cookie
     * @return string
     */
    public static function cookieToString(object $cookie): string
    {
        return collect($cookie)->reduce(function ($res, $v, $k) {


            return "{$res}{$k}='{$v}'; ";
        }, '');

    }

    /**
     * @param $name
     * @return object
     */
    public static function getCookie($name): object
    {
        return json_decode(File::get(base_path(Constants::SESSIONS_PATH . $name . '.json')));

    }

    /**
     * @param array $data
     * @param string $file
     */
    public static function putJson(array $data, string $file): void
    {
        File::put(base_path($file . '.json'), json_encode($data));
    }

    /**\
     * @param string $string
     * @return object
     */
    public static function jsonDecode(string $string = ''): object
    {
        if ($string) {
            return json_decode($string);
        }
        return collect();
    }


    /**
     * @param string $str
     * @param string $start
     * @param string $end
     * @return string
     */
    public static function searchInString(string $str, string $start, string $end): string
    {
        $pattern = sprintf(
            '/%s(.+?)%s/ims',
            preg_quote($start, '/'), preg_quote($end, '/')
        );

        if (preg_match($pattern, $str, $matches)) {
            list(, $match) = $matches;
            return $match;
        }
        return 'UNKNOWN';
    }
}
