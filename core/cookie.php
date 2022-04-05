<?php

namespace core;

class Cookie {
    public function set($key, $value){
        /** @param true httpOnly */
        setcookie($key, $value,
            time() + $_ENV['COOKIE_EXPIRES_IN'] * 24 * 60 * 60, null, null, null, true);
    }

    public function setForCaptcha($key, $value){
        /** @param true httpOnly */
        // expires in 1 hour
        setcookie($key, $value, time() + $_ENV['CAPTCHA_EXPIRES_IN'] * 60 * 60, null, null, null, true);
    }

    public function get($key){
        return $_COOKIE[$key] ?? false;
    }

    public function remove($key){
        setcookie($key, "remove", time() - 360);
    }
}
