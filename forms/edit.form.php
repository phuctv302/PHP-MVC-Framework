<?php

namespace forms;

use core\Model;
use validators\NumberValidator;
use validators\RequireValidator;
use validators\StringValidator;

class EditForm extends Model {

    public $firstname = '';
    public $lastname = '';
    public $job_title = '';
    public $photo = '';
    public $birthday = '';
    public $phone = '';
    public $address = '';

    public function rules(){
        return [
            'firstname' => [new RequireValidator(), new StringValidator()],
            'lastname' => [new RequireValidator(), new StringValidator()],
            'phone' => [new RequireValidator(), new NumberValidator()],
            'job_title' => [new RequireValidator()],
            'address' => [new RequireValidator()],
        ];
    }
}
