<?php

namespace core;

interface Validator {
    public function validate($value);

    public function error();
}
