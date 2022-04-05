<?php

namespace validators;

use core\Validator;

class EmailValidator implements Validator {

    public function validate($value){
        return !filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function error(){
        return 'Invalid email address';
    }
}
