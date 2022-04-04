<?php

namespace core\middlewares;

use core\Application;
use core\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware{
    public $actions = [];

    public function __construct($actions = []){
        $this->actions = $actions;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute(){
        // not logged in yet
        if (Application::isGuest()){
            if (in_array(Application::$app->controller->action, $this->actions)){
                Application::$app->session->setFlash('error', 'Please login to continue!');
                Application::$app->response->redirect('/login');
            }
        }
    }
}
