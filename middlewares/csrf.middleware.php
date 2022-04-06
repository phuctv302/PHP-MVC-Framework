<?php

namespace middlewares;

use core\BaseMiddleware;
use core\Session;

class CsrfMiddleware extends BaseMiddleware {

    public function execute(){
        $body = $this->getRequest()->getBody();

        // Check csrf attack
        if (!isset($body[Session::CSRF_TOKEN_KEY])
            || $body[Session::CSRF_TOKEN_KEY] !== $this->getSession()->get(Session::CSRF_TOKEN_KEY)){
            $this->getSession()->setFlash(
                'error', 'CSRF Attacking! We\'re not gonna submit your form.');

            $currentPath = $this->getRequest()->getPath();
            if ($currentPath === '/profile-image' || $currentPath === '/logout'){
                $currentPath = '/profile';
            }

            $this->getResponse()->redirect($currentPath);
            exit;
        }
    }
}
