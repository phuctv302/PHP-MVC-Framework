<?php

namespace models;

use core\Application;
use core\Model;

class EditForm extends Model {

    public string $firstname = '';
    public string $lastname = '';
    public string $job_title = '';
    public string $email = '';

    public function rules(): array{
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'job_title' => [self::RULE_REQUIRED],
        ];
    }

    public function updateUser($data){
        $user = User::updateOne(['email' => $this->email], $data);

        return Application::$app->updateUser($user);
    }
}
