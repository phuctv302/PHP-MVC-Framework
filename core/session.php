<?php
namespace core;

class Session{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct(){
        session_start();
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flash_messages as $key => $flash_message){
            // Mark to be removed
            $flash_messages['remove'] = true;
        }

        $_SESSION[self::FLASH_KEY] = $flash_messages;
    }


    public function setFlash($key, $message){
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key){

    }
}
