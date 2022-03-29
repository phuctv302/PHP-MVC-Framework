<?php

namespace core;

class Cookie {
    public function set($key, $value){
        setcookie($key, $value,
            time() + $_ENV['COOKIE_EXPIRES'] * 24 * 60 * 60, null, null, null, true);
    }

    public function get($key){
        return $_COOKIE[$key] ?? false;
    }

    public function remove($key){
        setcookie($key, "remove", time() - 360);
    }
}
