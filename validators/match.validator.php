<?php

namespace validators;

use core\Validator;

class MatchValidator implements Validator {

    public $model;
    public $match_attr;

    public function __construct($model, $match_attr){
        $this->model = $model;
        $this->match_attr = $match_attr;
    }

    public function validate($value){
        return $value !== $this->model->{$this->match_attr};
    }

    public function error(){
        return "This field must be the same as " .
            $this->model->getLabel($this->match_attr);
    }
}
