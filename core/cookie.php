<?php

namespace core;

class Cookie {
    public function set($key, $value, $expiresIn){
        setcookie($key, $value,
            time() + $expiresIn * 24 * 60 * 60, null, null, null, true);
    }

    public function get($key){
        return $_COOKIE[$key] ?? false;
    }

    public function remove($key){
        setcookie($key, "remove", time() - 360);
    }
}
