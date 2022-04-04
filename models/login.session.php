<?php

namespace models;

use core\db\DbModel;

class LoginSession extends DbModel {

    public $login_token = '';
    public $user_id = '';
    public $expired_at = '';

    public static function tableName(){
        return 'login_sessions';
    }

    public function attributes(){
        return ['login_token', 'user_id', 'expired_at'];
    }

    public static function primaryKey(){
        return 'login_token';
    }

    public static function foreignKey(){
        return 'user_id';
    }

    public function rules(){
        return [
            'login_token' => [self::RULE_REQUIRED],
            'user_id' => [self::RULE_REQUIRED],
            'expired_at' => [self::RULE_REQUIRED]
        ];
    }
}
