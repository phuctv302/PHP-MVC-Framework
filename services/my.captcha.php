<?php

namespace services;

class MyCaptcha {
    // API key configuration
    public static $SITE_KEY = '6Lc3UyUfAAAAABb5JaeeRE7av5mdN4_aSPIZcilB';

    // verify response
    public static function verifyResponse($code){
        $secret = $_ENV['SECRET_KEY'];

        if (is_string($code)){
            $response = $code;
        } else {
            return true;
        }

        $remote_ip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remote_ip";
        $data = file_get_contents($url);
        $row = json_decode($data, true);

        return $row['success'];
    }

    public static function increaseCounter($body, $cookie){
        if (isset($body['submit'])){
            if (!$cookie->get('count')){
                $cookie->setForCaptcha('count', 1);
            } else {
                $count = $_COOKIE['count'] + 1;
                $cookie->setForCaptcha('count', $count);
            }
        }
    }
}
