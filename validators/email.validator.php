<?php

namespace validators;

use core\Validator;

class EmailValidator implements Validator {

    public static function validate($value, $rule, $model = null, $attribute = null){
        return !filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
