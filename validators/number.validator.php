<?php

namespace validators;

use core\Validator;

class NumberValidator implements Validator {

    public static function validate($value, $rule = null, $model = null, $attribute = null){
        return !preg_match("/^[0-9]+$/", $value);
    }
}
