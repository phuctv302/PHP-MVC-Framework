<?php

namespace models;

use core\db\DbModel;
use utils\DateConverter;
use utils\TokenGenerator;
use validators\RequireValidator;

// Save user to session or cookie in a more secure way
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

    public function rules(){
        return [
            'login_token' => [new RequireValidator()],
            'user_id' => [new RequireValidator()],
            'expired_at' => [new RequireValidator()]
        ];
    }

    /** @var $user \models\User */
    public static function create($user){
        $login_session = new LoginSession();

        $primary_key = $user->primaryKey();
        $primary_value = $user->{$primary_key};

        // sign new token and save to login_sessions table
        $login_token = TokenGenerator::signToken();
        $expired_at = DateConverter::toDate(time() + 7 * 24 * 60 * 60); // expires after 7 days
        $login_session->loadData([
            'login_token' => $login_token,
            'user_id' => $primary_value,
            'expired_at' => $expired_at
        ]);

        return $login_session;
    }
}
