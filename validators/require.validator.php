<?php

namespace validators;

use core\Validator;

class RequireValidator implements Validator {

    public function validate($value){
        return !$value;
    }

    public function error(){
        return "This field is required";
    }
}
