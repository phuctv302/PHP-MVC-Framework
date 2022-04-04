<?php

namespace validators;

use core\Validator;

class MaxValidator implements Validator {

    public static function validate($value, $rule, $model = null, $attribute = null){
        return strlen($value) > $rule['max'];
    }
}
