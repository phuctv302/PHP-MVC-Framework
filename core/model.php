<?php

namespace core;

abstract class Model{

    public function loadData($data){
        foreach ($data as $key => $value){
            if (property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    public function filterFields($data, $allowed_fields= []){
        $filter_data = [];
        foreach ($allowed_fields as $allowed_field){
            if (in_array($allowed_field, array_keys($data))){
                $filter_data[$allowed_field] = $data[$allowed_field];
            }
        }
        return $filter_data;
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
        foreach ($this->rules() as $attribute => $validators){
            $value = $this->{$attribute};
            /** e.g: @var $validator \validators\RequireValidator */
            foreach ($validators as $validator){
                if ($validator->validate($value)){
                    $this->addError($attribute, $validator->error());
                }
            }
        }

        return empty($this->errors); // errors is empty means NO ERROR
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
}
