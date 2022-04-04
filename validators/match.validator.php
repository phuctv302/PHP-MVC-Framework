<?php

namespace validators;

use core\Validator;

class MatchValidator implements Validator {

    public static function validate($value, $rule, $model, $attribute = null){
        return $value !== $model->{$rule['match']};
    }
}
