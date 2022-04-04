<?php

namespace validators;

use core\Validator;

class RequireValidator implements Validator {

    public static function validate($value, $rule = null, $model = null, $attribute = null){
        return !$value;
    }
}
