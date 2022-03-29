<?php

namespace models;

use core\Model;
use core\Email;

class ForgotForm extends Model {

    public string $email = '';

    public function rules(): array{
        return [
            'email' => [self::RULE_REQUIRED.  self::RULE_EMAIL]
        ];
    }

    public function sendToken(){
        // find user with input email
        $user = User::findOne(['email' => $this->email]);
        if (!$user){
            $this->addError('email', 'User does not exist with this email');
            return false;
        }

        // create a random reset token and TODO: save to users table
        $reset_token = bin2hex(random_bytes(32));
        User::updateOne(['email' => $this->email], ['reset_token' => $reset_token]);

        // get url to reset password: reset?token=.....
        $url = "http://127.0.0.1:8080/reset?token=$reset_token";

        // config email options
        $email = new Email($user, $url);

        // send mail
        return $email->sendPasswordReset();
    }
}