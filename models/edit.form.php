<?php

namespace models;

use core\Application;
use core\Model;

class EditForm extends Model {

    public $firstname = '';
    public $lastname = '';
    public $job_title = '';
    public $photo = '';

    public function updateUser($data){
        $filterData = $this->filterFields($data, ['firstname', 'lastname', 'job_title', 'photo']);
        User::updateOne([User::primaryKey() => $_COOKIE['user']], $filterData);
        return true;
    }

    public function rules(): array{
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED]
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
