<?php

namespace core;

/*
 * For validating, load data input into form
 * */
abstract class Model {

    /**
     * @param array $data load into form
     * Only attribute in the Child class is passed data
     * */
    public function loadData($data){
        foreach ($data as $key => $value){
            if (property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Filter the data fields we want
     * @param array $data original data
     * @param array $allowed_fields fields we want to filter
     * @return array data after being filtered
     * */
    public static function filterFields($data, $allowed_fields = []){
        $filter_data = [];
        foreach ($allowed_fields as $allowed_field){
            if (in_array($allowed_field, array_keys($data))){
                $filter_data[$allowed_field] = $data[$allowed_field];
            }
        }
        return $filter_data;
    }

    // Rules for each field of form
    abstract public function rules();

    // labels corresponding to each attribute
    public function labels(){
        return [];
    }

    public function getLabel($attribute){
        return $this->labels()[$attribute] ?? $attribute;
    }

    // errors of each input
    public $errors = [];

    /**
     * validate for each input field from user
     * @return true if No error
     * otherwise @return false
     * */
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

    /**
     * Add error into errors
     * @param string $attribute the input fields to be added error
     * @param string $message error message
     * */
    public function addError($attribute, $message){
        $this->errors[$attribute][] = $message;
    }

    /**
     * Check if the input field has error or not
     * @return true if having at least an error
     * @return false if no error
     * */
    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    /**
     * @return string first error in errors
     * */
    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }
}
