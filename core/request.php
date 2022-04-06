<?php

namespace core;

// Get the current path form browser, current method and get body
class Request {
    public function getPath(){
        // $_SERVER['REQUEST_URI'] returns path include query string: /path?id=1
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        // Get the position of '?'
        $position = strpos($path, '?');
        if ($position === false){
            return $path; // if no '?' return path (/path)
        }

        return substr($path, 0, $position); // remove query string
    }

    /**
     * @return string current method (lowercase)
     * */
    public function method(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get data from get/post, make data more secure
     * @return array sanitized data
     * */
    public function getBody(){
        $body = [];
        if ($this->method() === 'get'){
            foreach ($_GET as $key => $value){
                // sanitize data
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === 'post'){
            // if user submit file image
            if (isset($_FILES['photo'])){
                $_POST['photo'] = $_FILES['photo']['name'];
            }

            foreach ($_POST as $key => $value){
                // sanitize data
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
