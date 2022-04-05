<?php

namespace services;

// TODO: move to service
use core\Application;

class MyCaptcha {
    // API key configuration
    public static $SITE_KEY = '6Lc3UyUfAAAAABb5JaeeRE7av5mdN4_aSPIZcilB';
    public static $SECRET_KEY = '6Lc3UyUfAAAAAOK4_QO3TLEwkjsOuAxdIMCWDxna';

    // verify response
    // TODO: get submit from request obj, better through param, should be static
    public static function verifyResponse($body){
        if (isset($body['submit'])){
            $secret = self::$SECRET_KEY;

            if (isset($body['g-recaptcha-response'])){
                $response = $body['g-recaptcha-response'];
            } else {
                return true;
            }

            $remote_ip = $_SERVER['REMOTE_ADDR'];
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remote_ip";
            $data = file_get_contents($url);
            $row = json_decode($data, true);

            return $row['success'];
        }
    }

    public static function increaseCounter($body){
        if (isset($body['submit'])){
            if (!Application::$app->cookie->get('count')){
                Application::$app->cookie->set('count', 1, 1);
            } else {
                $count = $_COOKIE['count'] + 1;
                Application::$app->cookie->set('count', $count, 1);
            }
        }
    }

    public static function reset(){
        Application::$app->cookie->remove('count');
    }

}
