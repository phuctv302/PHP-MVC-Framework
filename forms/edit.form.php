<?php

namespace forms;

use core\Model;
use validators\NumberValidator;
use validators\RequireValidator;
use validators\StringValidator;

/*
 * All form class in this folder will validate input value from user
 * */
class EditForm extends Model {

    /*
     * All attributes in form class is the input's name
     * */
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
