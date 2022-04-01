<?php

namespace models;

use core\Model;

class ImageForm extends Model {

    public $photo = '';

    public function rules(): array{
        return [];
    }

    public function uploadUserPhoto($newPhoto){
        $filterData = $this->filterFields($newPhoto, ['photo']);
        if (User::updateOne([User::primaryKey() => $_COOKIE['user']], $filterData)){
            return true;
        } else {
            return false;
        }
    }

    public function filterFields($data, $allowedFields= []){
        $filterData = [];
        foreach ($allowedFields as $allowedField){
            if (in_array($allowedField, array_keys($data))){
                $filterData[$allowedField] = $data[$allowedField];
            }
        }

        return $filterData;
    }
}
