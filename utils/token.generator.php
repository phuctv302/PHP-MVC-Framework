<?php

namespace utils;

class TokenGenerator {
    public static function signToken(){
        return base64_encode(openssl_random_pseudo_bytes(32));
    }
}
