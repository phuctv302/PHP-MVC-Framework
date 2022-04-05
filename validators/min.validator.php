<?php

namespace validators;

use core\Validator;

class MinValidator implements Validator {

    private $min;

    public function __construct($min){
        $this->min = $min;
    }


    public function validate($value){
        return strlen($value) < $this->min;
    }

    public function error(){
        return "Min length of this field must be $this->min";
    }
}
