<?php

namespace core;

abstract class BaseMiddleware{
    abstract public function execute();

    public function getCookie(){
        return Application::$app->cookie;
    }

    public function getSession(){
        return Application::$app->session;
    }

    public function getResponse(){
        return Application::$app->response;
    }

    public function getRequest(){
        return Application::$app->request;
    }

    public function setUser($user){
        Application::$app->user = $user;
    }
}
