<?php

namespace core;

use validators\EmailValidator;
use validators\MatchValidator;
use validators\MaxValidator;
use validators\MinValidator;
use validators\NumberValidator;
use validators\RequireValidator;
use validators\StringValidator;
use validators\UniqueValidator;

abstract class Model{

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_STRING = 'string';
    public const RULE_NUMBER = 'number';

    public function loadData($data){
        foreach ($data as $key => $value){
            if (property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    abstract public function rules();

    public function labels(){
        return [];
    }

    public function getLabel($attribute){
        return $this->labels()[$attribute] ?? $attribute;
    }

    public $errors = [];

    public function validate(){
        foreach ($this->rules() as $attribute => $rules){
            $value = $this->{$attribute};
            foreach ($rules as $rule){
                $rule_name = $rule;
                if (!is_string($rule_name)){
                    $rule_name = $rule[0];
                }
                if ($rule_name === self::RULE_REQUIRED
                    && RequireValidator::validate($value)){
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($rule_name === self::RULE_EMAIL
                    && EmailValidator::validate($value, $rule)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($rule_name === self::RULE_MIN
                    && MinValidator::validate($value, $rule)){
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($rule_name === self::RULE_MAX
                    && MaxValidator::validate($value, $rule)){
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($rule_name === self::RULE_MATCH
                    && MatchValidator::validate($value, $rule, $this)){
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($rule_name === self::RULE_STRING
                    && StringValidator::validate($value)){
                    $this->addErrorForRule($attribute, self::RULE_STRING);
                }
                if ($rule_name === self::RULE_NUMBER
                    && NumberValidator::validate($value)){
                    $this->addErrorForRule($attribute, self::RULE_NUMBER);
                }
                if ($rule_name === self::RULE_UNIQUE
                    && UniqueValidator::validate($value, $rule, $this, $attribute)){
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                }
            }
        }

        return empty($this->errors); // errors is empty means NO ERROR
    }

    private function addErrorForRule($attribute, $rule, $params = []){
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function addError($attribute, $message){
        $this->errors[$attribute][] = $message;
    }

    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }

    public function errorMessages(){
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
            self::RULE_STRING => 'This field contains only string',
            self::RULE_NUMBER => 'This field contains only number'
        ];
    }
}
