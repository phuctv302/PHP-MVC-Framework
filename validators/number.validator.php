<?php

namespace validators;

use core\Validator;

class NumberValidator implements Validator {

    public function validate($value){
        return !preg_match("/^[0-9]+$/", $value);
    }

    public function error(){
        return "This field must contain only number";
    }
}
