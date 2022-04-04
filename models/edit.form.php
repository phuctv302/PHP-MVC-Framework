<?php

namespace models;

use core\Application;
use core\Model;
use services\ImageUploadService;
use validators\RequireValidator;

class EditForm extends Model {

    public $firstname = '';
    public $lastname = '';
    public $job_title = '';
    public $photo = '';
    public $birthday = '';
    public $phone = '';
    public $address = '';

    public function updateUser($data){
        //TODO: check through image service
        if (!empty($_FILES['photo']['tmp_name'])) {
            $filterData = $this->filterFields($data,
                ['firstname', 'lastname', 'job_title', 'photo', 'address', 'birthday', 'phone']);
        } else {
            $filterData = $this->filterFields($data, ['firstname', 'lastname', 'job_title', 'address', 'birthday', 'phone']);
        }

        // TODO: persistent ops should be in controllers
        if (User::updateOne([User::primaryKey() => Application::$app->user->id], $filterData)){
            return true;
        } else {
            return false;
        }
    }

    public function rules() {
        return [

            //TODO: OOP!!!!
            'firstname' => [RequireValidator::class, self::RULE_STRING],
            'lastname' => [self::RULE_REQUIRED, self::RULE_STRING],
            'phone' => [self::RULE_NUMBER]
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
