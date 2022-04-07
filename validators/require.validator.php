<?php

namespace validators;

use core\Validator;

// Check if value is empty or not
class RequireValidator implements Validator {

    public function validate($value){
        return !$value;
    }

    public function error(){
        return "This field is required";
    }
}
