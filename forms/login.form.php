<?php

namespace forms;

use core\Model;
use validators\EmailValidator;
use validators\RequireValidator;

class LoginForm extends Model {

    public $email = '';
    public $password = '';
    public $save_auth = '';

    public function rules(): array{
        return [
            'email' => [new RequireValidator(), new EmailValidator()],
            'password' => [new RequireValidator()]
        ];
    }

    public function labels(): array{
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }
}
