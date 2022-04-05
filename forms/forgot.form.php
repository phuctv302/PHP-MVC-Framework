<?php

namespace forms;

use core\Model;
use models\User;
use services\Email;
use utils\TokenGenerator;
use validators\EmailValidator;
use validators\RequireValidator;

class ForgotForm extends Model {

    public $email = '';

    public function rules(): array{
        return [
            'email' => [new RequireValidator(),  new EmailValidator()]
        ];
    }
}