<?php

namespace services;

use core\Application;

class ImageUploadService {

    // check photo is posted?
    public static function checkImageExist($attribute){
        return isset($_FILES[$attribute])&& !empty($_FILES[$attribute]['tmp_name']);
    }

    // TODO: generalize service: add ($attribute, $path) as params
    public static function upload($attribute, $path){
        // check if user post image
        if (strpos($_FILES[$attribute]['type'], 'image') !== 0){
            Application::$app->session->setFlash('error', 'Please choose an image!');
            return false;
        } else {
            // copy image to public/img/users/user-{id}-{time()}

            move_uploaded_file($_FILES[$attribute]['tmp_name'], $path);
        }
    }
}
