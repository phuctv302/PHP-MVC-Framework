<?php

namespace core;

interface Validator {
    public static function validate($value, $rule, $model, $attribute);
}
