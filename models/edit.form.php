<?php

namespace models;

use core\Application;
use core\Model;

class EditForm extends Model {

    public $firstname = '';
    public $lastname = '';
    public $job_title = '';
    public $photo = '';
    public $birthday = '';
    public $phone = '';
    public $address = '';

    public function updateUser($data){
        if (!empty($_FILES['photo']['tmp_name'])) {
            $filterData = $this->filterFields($data,
                ['firstname', 'lastname', 'job_title', 'photo', 'address', 'birthday', 'phone']);
        } else {
            $filterData = $this->filterFields($data, ['firstname', 'lastname', 'job_title', 'address', 'birthday', 'phone']);
        }

        if (User::updateOne([User::primaryKey() => $_COOKIE['user'] ?? $_SESSION['user']]
            , $filterData)){
            return true;
        } else {
            return false;
        }
    }

    public function rules(): array{
        return [
            'firstname' => [self::RULE_REQUIRED, self::RULE_STRING],
            'lastname' => [self::RULE_REQUIRED, self::RULE_STRING]
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
