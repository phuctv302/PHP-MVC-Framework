<?php

namespace models;

use core\Model;

class ResetForm extends Model {
    public string $password;
    public string $confirm_password;

    public function rules(): array{
        return [
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'confirm_password' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function resetPassword(){
        // get reset token from url
        $reset_token = explode('=', $_SERVER['REQUEST_URI'])[1];

        // find user with that token
        $user = User::findOne(['reset_token' => $reset_token]);
        if (!$user){
            $this->addError('reset_token', 'Reset token is invalid!');
            return false;
        }

        // update
        User::updateOne(['email' => $user->email],
            ['password' => password_hash($this->password, PASSWORD_DEFAULT), 'reset_token' => '']);
        return true;
    }
}