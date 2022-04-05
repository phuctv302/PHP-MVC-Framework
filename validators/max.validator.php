<?php

namespace validators;

use core\Validator;

class MaxValidator implements Validator {

    public $max;

    public function __construct($max){
        $this->max = $max;
    }

    public function validate($value){
        return strlen($value) > $this->max;
    }

    public function error(){
        return "Max length of this field must be $this->max";
    }
}
