<?php

namespace core;

class Controller {
    public $layout = 'main';
    public $action = '';

    public function setLayout($layout){
        $this->layout = $layout;
    }

    public function render($view, $params = []){
        return Application::$app->view->render($view, $params);
    }


    protected function isExisted($value, $class_name, $unique_attr){
        $table_name = $class_name::tableName();
        $statement = Application::$app->db->prepare(
            "SELECT * FROM $table_name WHERE $unique_attr = :attr"
        );

        $statement->bindValue(":attr", $value);
        $statement->execute();
        $record = $statement->fetchObject();
        if ($record){
            return true; // true means this record has already existed in table
        }
        return false;
    }

    public function getCookie(){
        return Application::$app->cookie;
    }

    public function getSession(){
        return Application::$app->session;
    }

    public function getUser(){
        return Application::$app->user;
    }

    public function setUser($user){
        Application::$app->user = $user;
    }

    public function getApp(){
        return Application::$app;
    }
}
