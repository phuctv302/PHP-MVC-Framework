<?php

namespace forms;

use core\Model;
use validators\MatchValidator;
use validators\MaxValidator;
use validators\MinValidator;
use validators\RequireValidator;

class ResetForm extends Model {
    public $password;
    public $confirm_password;

    public function rules(): array{
        return [
            'password' => [new RequireValidator(), new MinValidator(8), new MaxValidator(24)],
            'confirm_password' => [new RequireValidator(), new MatchValidator($this, 'password')]
        ];
    }
}
