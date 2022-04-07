<?php

namespace middlewares;

use core\BaseMiddleware;
use core\exceptions\ForbiddenException;
use models\LoginSession;
use models\User;
use utils\DateConverter;

// Check user is logged in or not
class AuthMiddleware extends BaseMiddleware {

    /**
     * @throws ForbiddenException
     */
    public function execute(){

        $login_token = $this->getCookie()->get('user') ?: $this->getSession()->get('user');

        if (!$login_token){
            $this->getSession()->setFlash('error', 'Please login to continue!');
            $this->getResponse()->redirect('/login');
        }

        // find the login session by login_token
//        $login_session = LoginSession::findOne(
//            ['login_token' => $login_token, 'expired_at' => DateConverter::toDate(time())]
//        );
        $login_session = LoginSession::findOne(['login_token' => $login_token]);

        // Check if login_session is expired or not
        if (DateConverter::toDate(time()) > $login_session->expired_at){
            $this->getSession()->setFlash('error', 'Please login to continue!');
            $this->getResponse()->redirect('/login');
        }

        $user = User::findOne(['id' => $login_session->user_id]);
        if (!$user){
            $this->getSession()->setFlash('error', 'Please login to continue!');
            $this->getResponse()->redirect('/login');
        }

        // ON SUCCESS => save user to app
        $this->setUser($user);
    }
}
