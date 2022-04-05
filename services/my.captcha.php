<?php

namespace services;

use core\Application;

class MyCaptcha {
    // API key configuration
    public static $SITE_KEY = '6Lc3UyUfAAAAABb5JaeeRE7av5mdN4_aSPIZcilB';
    public static $SECRET_KEY = '6Lc3UyUfAAAAAOK4_QO3TLEwkjsOuAxdIMCWDxna';

    // verify response
    // TODO: pass captcha code directly
    public static function verifyResponse($code){
        $secret = self::$SECRET_KEY;

        if (isset($code)){
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

    public static function increaseCounter($body){
        if (isset($body['submit'])){
            if (!Application::$app->cookie->get('count')){
                Application::$app->cookie->setForCaptcha('count', 1);
            } else {
                $count = $_COOKIE['count'] + 1;
                Application::$app->cookie->setForCaptcha('count', $count);
            }
        }
    }

    public static function reset(){
        Application::$app->cookie->remove('count');
    }

}
