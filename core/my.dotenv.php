<?php

namespace core;

// Load environment variables
class MyDotenv {
    protected $path;

    public function __construct($path){
        if (!file_exists($path)){
            throw new \InvalidArgumentException(sprintf("%s does not exist", $path));
        }
        $this->path = $path;
    }

    public function load(){
        if (!is_readable($this->path)){
            throw new \RuntimeException(sprintf("%s file is not readable", $this->path));
        }

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line){
            // ignore the comment line
            if (strpos(trim($line), '#') === 0){
                continue;
            }

            // list: destruct array [name, value]
            // explode: like split function in JS
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            // Check and set environment variables
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)){
                putenv(sprintf("%s=%s", $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
