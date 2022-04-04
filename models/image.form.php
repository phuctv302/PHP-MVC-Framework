<?php

namespace models;

use core\Application;
use core\Model;

class ImageForm extends Model {

    public $photo = '';

    public function rules(): array{
        return [];
    }

    public function uploadUserPhoto($newPhoto){
        $filter_data = $this->filterFields($newPhoto, ['photo']);
        if (User::updateOne([User::primaryKey() => Application::$app->user->id], $filter_data)){
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
