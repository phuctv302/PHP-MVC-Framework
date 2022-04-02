<?php

namespace models;

use core\Model;
use core\Email;

class ForgotForm extends Model {

    public $email = '';

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

        // create a random reset token
        $reset_token = bin2hex(random_bytes(32));
        User::updateOne(['email' => $this->email], ['reset_token' => $reset_token]);

        // get url to reset password: reset?token=.....
        $url = $this->getUrl() . "/reset?token=$reset_token";

        // config email options
        $email = new Email($user, $url);

        // send mail
        return $email->sendPasswordReset();
    }

    public function getUrl(){
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'http') === 0 ? 'http' : 'https';
        $host = $_SERVER['SERVER_NAME'];
        return $protocol . '://' . $host;
    }
}