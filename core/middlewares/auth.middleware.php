<?php

namespace core\middlewares;

use core\Application;
use core\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware{

    /**
     * @throws ForbiddenException
     */
    public function execute(){
        // not logged in yet
        if (Application::isGuest()){
            Application::$app->session->setFlash('error', 'Please login to continue!');
            Application::$app->response->redirect('/login');
        }
    }
}
