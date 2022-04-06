<?php

namespace core;

/*
 * Set the layout, render view and check unique attribute
 * */
class Controller {
    public $layout = 'main';

    /**
     * @var string $action is actually a method from one Controller which @extends $this
     * */
    public $action = '';

    public function setLayout($layout){
        $this->layout = $layout;
    }

    public function render($view, $params = []){
        return Application::$app->view->render($view, $params);
    }

    /**
     * Check unique attribute
     * @param string $value the value of the @param $unique_attr
     * @param string $class_name name of class (table) including namespace
     * @param string $unique_attr
     * @return true of existed
     * otherwise @return false
     * */
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

    /**
     * methods for not calling Application class outside @package core
     * */
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
