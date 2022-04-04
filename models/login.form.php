<?php

namespace models;

use core\Application;
use core\Model;
use utils\DateConverter;
use utils\TokenGenerator;

class LoginForm extends Model{

    public $email = '';
    public $password = '';

    public function rules(): array{
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array{
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    public function login(){
        $user = User::findOne(['email' => $this->email]);
        if (!$user){
            $this->addError('email', 'User does not exist with this email');
            return false;
        }
        if (!password_verify($this->password, $user->password)){
            $this->addError('password', 'Password is incorrect');
            return false;
        }

        return Application::$app->login($user);
    }

    /** @var $user \models\User
     */
    public function signToken($user){
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
        $login_session->save();

        return $login_token;
    }
}
