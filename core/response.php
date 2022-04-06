<?php

namespace core;

// Set status code and redirect
class Response {

    /**
     * @param int $code status code of response @example: 404 - not found
     * */
    public function setStatusCode($code){
        http_response_code($code);
    }

    /**
     * Navigate user to path passed in
     * @param string $url the path we want to navigate @example: '/login'
     * */
    public function redirect($url){
        header('Location: ' . $url);
        exit;
    }
}
