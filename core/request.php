<?php

namespace core;

class Request{
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

    public function method(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(){
        return $this->method() === 'get';
    }

    public function isPost(){
        return $this->method() === 'post';
    }

    public function getBody(){
        $body = [];
        if ($this->method() === 'get'){
            foreach ($_GET as $key => $value){
                // sanitize data
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === 'post'){
            foreach ($_POST as $key => $value){
                // sanitize data
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);

                // for upload user photo
                if (empty($body['photo'])){
                    $body['photo'] = Application::$app->user->photo ?? 'default.jpg';
                }
            }
        }

        return $body;
    }
}
