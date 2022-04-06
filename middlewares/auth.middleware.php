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
        $login_session = LoginSession::findOne(
            ['login_token' => $login_token, 'expired_at' => DateConverter::toDate(time())]
        );

        $user = User::findOne(['id' => $login_session->user_id]);
        if (!$user){
            $this->getSession()->setFlash('error', 'Please login to continue!');
            $this->getResponse()->redirect('/login');
        }

        // ON SUCCESS
        $this->setUser($user);
    }
}
