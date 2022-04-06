<?php

namespace core;

// Validator for input fields from user
interface Validator {
    public function validate($value);

    public function error();
}
