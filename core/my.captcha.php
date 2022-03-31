<?php

namespace core;

class MyCaptcha {
    // API key configuration
    public static $siteKey = '6Lc3UyUfAAAAABb5JaeeRE7av5mdN4_aSPIZcilB';
    public static $secretKey = '6Lc3UyUfAAAAAOK4_QO3TLEwkjsOuAxdIMCWDxna';

    // verify response
    public function verifyResponse(){
        if (isset($_POST['submit'])){
            $secret = self::$secretKey;

            if (isset($_POST['g-recaptcha-response'])){
                $response = $_POST['g-recaptcha-response'];
            } else {
                return true;
            }

            $remoteip = $_SERVER['REMOTE_ADDR'];
            $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip";
            $data = file_get_contents($url);
            $row = json_decode($data, true);

            return $row['success'];
        }
    }
}
