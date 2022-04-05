<?php

namespace forms;

use core\Model;

class EditForm extends Model {

    public $firstname = '';
    public $lastname = '';
    public $job_title = '';
    public $photo = '';
    public $birthday = '';
    public $phone = '';
    public $address = '';

    public function rules() {
        return [];
    }
}
