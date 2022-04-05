<?php

namespace models;

use core\Application;
use core\db\DbModel;
use utils\DateConverter;
use utils\TokenGenerator;
use validators\RequireValidator;

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
            'login_token' => [new RequireValidator()],
            'user_id' => [new RequireValidator()],
            'expired_at' => [new RequireValidator()]
        ];
    }

    /** @var $user \models\User
     */
    // TODO: move to login.session
    public static function createNewLoginSesison($user){
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
        // TODO: persistence operations should be at controller

        return $login_session;
    }

    // TODO: move to login session
    /** @var $login_form \forms\LoginForm */
    public static function login($login_form){
        $user = User::findOne(['email' => $login_form->email]);
        if (!$user){
            $login_form->addError('email', 'User does not exist with this email');
            return false;
        }
        if (!password_verify($login_form->password, $user->password)){
            $login_form->addError('password', 'Password is incorrect');
            return false;
        }

        return Application::$app->login($user);
    }
}
