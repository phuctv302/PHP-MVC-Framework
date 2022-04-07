<?php

namespace services;

// Verify captcha
class MyCaptcha {
    // verify response
    /**
     * @return true if captcha is checked or not displayed yet
     * otherwise @return false
     * */
    public static function verifyResponse($code){
        $secret = $_ENV['SECRET_KEY'];

        if (is_string($code)){
            $response = $code;
        } else {
            // captcha is not displayed yet
            return true;
        }

        $remote_ip = $_SERVER['REMOTE_ADDR'];
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remote_ip";
        $data = file_get_contents($url);
        $row = json_decode($data, true);

        return $row['success'];
    }

}
