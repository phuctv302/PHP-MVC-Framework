<?php

namespace services;

// save user image to folder
class ImageUploadService {

    /** @param string $attribute the name of the file input field */
    // check photo is posted?
    public static function checkFileExist($attribute){
        return isset($_FILES[$attribute]) && !empty($_FILES[$attribute]['tmp_name']);
    }

    /** @param string $attribute the name of the file input field
     *  @param string $path place to save image uploaded
     */
    public static function upload($attribute, $path){
        // copy image to public/img/users/user-{id}-{time()}
        move_uploaded_file($_FILES[$attribute]['tmp_name'], $path);
    }

    /**
     * Check if file uploaded is image or not
     *  @param string $attribute the name of the file input field
     */
    public static function isImage($attribute){
        return strpos($_FILES[$attribute]['type'], 'image') === 0;
    }
}
