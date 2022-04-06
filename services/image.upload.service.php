<?php

namespace services;

class ImageUploadService {

    // check photo is posted?
    public static function checkFileExist($attribute){
        return isset($_FILES[$attribute]) && !empty($_FILES[$attribute]['tmp_name']);
    }

    public static function upload($attribute, $path){
        // copy image to public/img/users/user-{id}-{time()}
        move_uploaded_file($_FILES[$attribute]['tmp_name'], $path);
    }

    public static function isImage($attribute){
        return strpos($_FILES[$attribute]['type'], 'image') === 0;
    }
}
