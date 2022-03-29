<?php

namespace models;

use core\Application;
use core\Model;

class EditForm extends Model {

    public string $firstname = '';
    public string $lastname = '';
    public string $job_title = '';

    public function updateUser($data){
        $filterData = $this->filterFields($data, ['firstname', 'lastname', 'job_title']);
        User::updateOne([User::primaryKey() => $_COOKIE['user']], $filterData);
        return true;
    }

    public function rules(): array{
        return [
            'firstname' => [self::RULE_REQUIRED]
        ];
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
