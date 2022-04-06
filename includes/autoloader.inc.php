<?php

// This function with automatically pass the param class_name into MyAutoLoader function
spl_autoload_register('myAutoLoader');

function myAutoLoader($class_name){
    $root = dirname(__DIR__);

    //    $ds = DIRECTORY_SEPARATOR; // "/"
    // $class_name: core\MyApplication
    // => root/core/application.php

    $full_path = $root . '\\' . formattedPath($class_name);

    if (!file_exists($full_path)){
        return false;
    }

    include_once $full_path;
}

/** e.g of @param $class_name : core\MyApplication.php
 * */
function formattedPath($class_name){
    $extension = ".php";

    // get the last word of class_name separated by "\"
    $last_slash_pos = strripos($class_name, "\\"); // the last index of "\"
    $post_class_name = substr($class_name, $last_slash_pos + 1, strlen($class_name));
    $pre_class_name = substr($class_name, 0, $last_slash_pos + 1);

    // split post_class_name by uppercase character
    $split_post_class_name = preg_split('/(?=[A-Z])/', $post_class_name, -1, PREG_SPLIT_NO_EMPTY);

    // join post_class_name array with dot (.)
    $post_class_name = strtolower(implode(".", $split_post_class_name));

    // join class_name with pre path
    $class_name = $pre_class_name . $post_class_name;

    // get the full_path
    return $class_name . $extension;
}
