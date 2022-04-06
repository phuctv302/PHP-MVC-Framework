<?php

namespace core;

/*
 * Set and get cookie
 * */
class Cookie {

    /**
     * @param string $key name of the cookie we want to store
     * @param string $value
     * */
    public function set($key, $value){
        /** @param true httpOnly */
        setcookie($key, $value,
            time() + $_ENV['COOKIE_EXPIRES_IN'] * 24 * 60 * 60, null, null, null, true);
    }

    /**
     * @param string $key name of the cookie we want to store
     * @param string $value
     * */
    public function setForCaptcha($key, $value){
        /** @param true httpOnly */
        // expires in 1 hour
        setcookie($key, $value, time() + $_ENV['CAPTCHA_EXPIRES_IN'] * 60 * 60, null, null, null, true);
    }

    // Get value of cookie by $Key
    public function get($key){
        return $_COOKIE[$key] ?? false;
    }

    /**
     * We can not actually remove the cookie
     * => override the existed cookie with other name and set past expired time
     * */
    public function remove($key){
        setcookie($key, "remove", time() - 360);
    }
}
