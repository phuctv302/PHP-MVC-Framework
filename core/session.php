<?php

namespace core;

use utils\TokenGenerator;

class Session {
    protected const FLASH_KEY = 'flash_messages';
    public const CSRF_TOKEN_KEY = 'csrf_token';

    public function __construct(){
        session_start();

        // SET NOTIFICATION
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        if (!empty($flash_messages)){
            foreach ($flash_messages as $_key => &$flash_message){
                // Mark to be removed
                $flash_message['remove'] = true;
            }
        }
        $_SESSION[self::FLASH_KEY] = $flash_messages;

        // SET CSRF TOKEN
        if (!isset($_SESSION[self::CSRF_TOKEN_KEY])){
            $_SESSION[self::CSRF_TOKEN_KEY] = TokenGenerator::signToken();
        }
    }


    public function setFlash($key, $message){
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key){

        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key] ?? false;
    }

    public function remove($key){
        unset($_SESSION[$key]);
    }

    public function __destruct(){
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];

        // remove flash message marked to be removed
        foreach ($flash_messages as $key => &$flash_message){
            if ($flash_message['remove']){
                unset($flash_messages[$key]);
            }
        }

        $_SESSION[self::FLASH_KEY] = $flash_messages;
    }
}
