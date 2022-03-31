<?php
spl_autoload_register('myAutoLoader');

function myAutoLoader($class_name){
        $path = dirname(__DIR__);

    //    $ds = DIRECTORY_SEPARATOR; // "/"
    // $class_name: core\MyApplication
    // => root/core/application.php

    //    $full_path = $path . '\\' . $class_name . $extension;
    $full_path = $path . '\\' . formattedPath($class_name);
//    echo $full_path;
    //    echo "<br>";

    if (!file_exists($full_path)){
        return false;
    }


    include_once $full_path;
}

/*
 * eg of class_name: core\MyApplication.php
 * */
function formattedPath($class_name){
    $extension = ".php";

    // get the last word of class_name separated by "\"
    $lastSlashPos = strripos($class_name, "\\"); // the last index of "\"
    $post_class_name = substr($class_name, $lastSlashPos+1, strlen($class_name));
    $pre_class_name = substr($class_name, 0, $lastSlashPos + 1);

    // split post_class_name by uppercase character
    $split_post_class_name =  preg_split('/(?=[A-Z])/', $post_class_name, -1, PREG_SPLIT_NO_EMPTY);

    // join post_class_name array with dot (.)
    $post_class_name = strtolower(implode(".", $split_post_class_name));


    // join class_name with pre path
    $class_name = $pre_class_name . $post_class_name;

    // get the full_path
    return $class_name . $extension;
}
