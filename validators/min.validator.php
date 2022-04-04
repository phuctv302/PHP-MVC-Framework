<?php

namespace validators;

use core\Validator;

class MinValidator implements Validator {

    private $min;
    private $errors;

    public function __construct($min) {
        $this->min = $min;
    }

    public static function validate($value, $rule, $model = null, $attribute = null){
        return strlen($value) < $rule['min'];
    }
}
