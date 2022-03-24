<?php
spl_autoload_register('myAutoLoader');

function myAutoLoader($class_name){
    //    $path = getcwd();
    $extension = ".php";

    $ds = DIRECTORY_SEPARATOR; // "/"
    // $class_name: core\MyApplication
    // => root/core/application.php

    // lowercase
    $class_name = strtolower($class_name);

    // replace slash
    //    $class_name = str_replace("\\", '/', $class_name);

    //    $full_path = $path . '\\' . $class_name . $extension;
    $full_path = $class_name . $extension;
    //    echo $full_path;
    //    echo "<br>";
    //    exit;

    if (!file_exists($full_path)){
        return false;
    }

    include_once $full_path;
}
