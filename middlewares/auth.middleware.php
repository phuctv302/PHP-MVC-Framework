<?php

namespace middlewares;

use core\Application;
use core\BaseMiddleware;
use core\exceptions\ForbiddenException;
use models\LoginSession;
use models\User;
use utils\DateConverter;

class AuthMiddleware extends BaseMiddleware{

    /**
     * @throws ForbiddenException
     */
    public function execute(){

        $login_token = Application::$app->cookie->get('user') ?: Application::$app->session->get('user');
        if (!$login_token){
            Application::$app->session->setFlash('error', 'Please login to continue!');
            Application::$app->response->redirect('/login');
        }
        $login_session = LoginSession::findOne(
            ['login_token' => $login_token, 'expired_at' => DateConverter::toDate(time())]
        );

        $user = User::findOne(['id' => $login_session->user_id]);
        // not logged in yet
        if (!$user){
            Application::$app->session->setFlash('error', 'Please login to continue!');
            Application::$app->response->redirect('/login');
        }

        Application::$app->user = $user;
    }
}
