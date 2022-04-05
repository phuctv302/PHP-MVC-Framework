<?php

namespace models;

use core\Application;
use core\db\DbModel;
use services\ImageUploadService;
use validators\EmailValidator;
use validators\MatchValidator;
use validators\MaxValidator;
use validators\MinValidator;
use validators\RequireValidator;
use validators\StringValidator;

class User extends DbModel {

    public $firstname = '';
    public $lastname = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $photo = 'default.jpg';
    public $confirm_password = '';
    public $job_title = '';
    public $birthday = '';
    public $phone = '';

    public static function tableName(){
        return 'users';
    }

    public static function primaryKey(){
        return 'id';
    }

    public function save(){
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function rules(){
        return [
            'firstname' => [new RequireValidator(), new StringValidator()],
            'lastname' => [new RequireValidator(), new StringValidator()],
            'email' => [new RequireValidator(), new EmailValidator()],
            'username' => [new RequireValidator()],
            'password' => [new RequireValidator(),
                new MinValidator(8),
                new MaxValidator(24)],
            'confirm_password' => [new RequireValidator(),
                new MatchValidator($this, 'password')]
        ];
    }

    public function attributes(){
        // return all database column name
        return ['firstname', 'lastname', 'email', 'photo', 'username', 'password'];
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

    public function getDisplayName(){
        return $this->lastname . ' ' . $this->firstname;
    }

    public function updateUser($edit_form){
        if (ImageUploadService::checkImageExist('photo')) {
            $filterData = $edit_form->filterFields((array) $edit_form,
                ['firstname', 'lastname', 'job_title', 'photo', 'address', 'birthday', 'phone']);
        } else {
            $filterData = $edit_form->filterFields((array) $edit_form, ['firstname', 'lastname', 'job_title', 'address', 'birthday', 'phone']);
        }

        if (User::updateOne([User::primaryKey() => $this->getUser()->id], $filterData)){
            return true;
        } else {
            return false;
        }
    }

    public function uploadUserPhoto($newPhoto){
        $filter_data = $this->filterFields((array) $newPhoto, ['photo']);
        if (User::updateOne([User::primaryKey() => $this->getUser()->id], $filter_data)){
            return true;
        } else {
            return false;
        }
    }

    /** @var $login_form \forms\LoginForm */
    public static function login($login_form){
        $user = self::findOne(['email' => $login_form->email]);
        if (!$user){
            $login_form->addError('email', 'User does not exist with this email');
            return false;
        }
        if (!password_verify($login_form->password, $user->password)){
            $login_form->addError('password', 'Password is incorrect');
            return false;
        }

        // ON SUCCESS
        Application::$app->user = $user;
        return true;
    }
}
