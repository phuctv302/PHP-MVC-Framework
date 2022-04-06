<?php

namespace utils;

class TokenGenerator {
    // Generate randome token
    public static function signToken(){
        return base64_encode(openssl_random_pseudo_bytes(32));
    }
}
