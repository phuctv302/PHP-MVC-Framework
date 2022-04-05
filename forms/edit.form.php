<?php

namespace forms;

use core\Application;
use core\Model;
use models\User;
use services\ImageUploadService;
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

    public function rules() {
        return [
            //TODO: OOP!!!!
            'firstname' => [new RequireValidator(), new StringValidator()],
            'lastname' => [new RequireValidator(), new StringValidator()],
            'phone' => [new NumberValidator()]
        ];
    }
}
