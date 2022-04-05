<?php

namespace middlewares;

use core\Application;
use core\BaseMiddleware;
use core\Session;

class CsrfMiddleware extends BaseMiddleware {

    public function execute(){
        $body = Application::$app->request->getBody();

        // Check csrf attack
        if (!isset($body[Session::CSRF_TOKEN_KEY])
            || $body[Session::CSRF_TOKEN_KEY] !== $_SESSION[Session::CSRF_TOKEN_KEY]){
            Application::$app->session->setFlash(
                'error', 'CSRF Attacking! We\'re not gonna submit your form.');

            $currentPath = Application::$app->request->getPath();
            if ($currentPath === '/profile-image' || $currentPath === '/logout'){
                $currentPath = '/profile';
            }

            Application::$app->response->redirect($currentPath);
            exit;
        }
    }
}
