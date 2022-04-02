<?php

namespace core;

class MyCaptcha {
    // API key configuration
    public static $SITE_KEY = '6Lc3UyUfAAAAABb5JaeeRE7av5mdN4_aSPIZcilB';
    public static $SECRET_KEY = '6Lc3UyUfAAAAAOK4_QO3TLEwkjsOuAxdIMCWDxna';

    // verify response
    public function verifyResponse(){
        if (isset($_POST['submit'])){
            $secret = self::$SECRET_KEY;

            if (isset($_POST['g-recaptcha-response'])){
                $response = $_POST['g-recaptcha-response'];
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
}
