<?php

namespace models;

use core\Model;

class ImageForm extends Model {

    public $photo = '';

    public function rules(): array{
        return [];
    }

    public function uploadUserPhoto($newPhoto){
        User::updateOne([User::primaryKey() => $_COOKIE['user']], $newPhoto);
        return true;
    }
}
