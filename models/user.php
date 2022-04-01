<?php

namespace models;

use core\UserModel;

class User extends UserModel{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $username = '';
    public $status = self::STATUS_INACTIVE;
    public $password = '';
    public $photo = 'default.jpg';
    public $confirm_password = '';
    public $job_title = '';

    public static function tableName(){
        return 'users';
    }

    public static function primaryKey(){
        return 'id';
    }

    public function save(){
        $this->status = self::STATUS_INACTIVE;
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function rules(){
        return [
            'firstname' => [self::RULE_REQUIRED, self::RULE_STRING],
            'lastname' => [self::RULE_REQUIRED, self::RULE_STRING],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'username' => [self::RULE_REQUIRED, [
                self::RULE_UNIQUE, 'class' => self::class
            ]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'confirm_password' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
    }

    public function attributes(): array{
        // return all database column name
        return ['firstname', 'lastname', 'email', 'photo', 'username', 'password', 'status', 'job_title'];
    }

    public function labels(){
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'username' => 'User name',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm password'
        ];
    }

    public function getDisplayName(): string{
        return $this->lastname . ' ' . $this->firstname;
    }
}
