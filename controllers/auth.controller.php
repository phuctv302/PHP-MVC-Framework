<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\middlewares\AuthMiddleware;
use services\MyCaptcha;
use core\Request;
use core\Response;
use models\EditForm;
use models\ForgotForm;
use models\ImageForm;
use models\LoginForm;
use models\LoginSession;
use models\ResetForm;
use models\User;
use utils\DateConverter;
use utils\TokenGenerator;

/** @var $request \core\Request
 * @var $response \core\Response
 */
class AuthController extends Controller {

    public function login($request, $response){
        $login_form = new LoginForm();
        $body = $request->getBody();

        $login_form->loadData($body);

//        if (!$login_form->validate()){
//            message
//            exit;
//        }
//        if (!$login_form->login()){
//            message
//            exit;
//        }
//        on success();

        if ($login_form->validate() && $login_form->login() && MyCaptcha::verifyResponse($body)){
            $user = Application::$app->user;

            // sign new login token and save it to database
            $login_token = $login_form->signToken($user);

            // TODO: get post data from request obj
            // if user check remember me checkbox
            if (isset($body['save-auth']) && $body['save-auth'] == 'on'){
                Application::$app->cookie->set('user', $login_token, $_ENV['COOKIE_EXPIRES']);
            } else if (!isset($body['save-auth'])){
                Application::$app->session->set('user', $login_token);
            }

            // reset captcha
            Application::$app->cookie->remove('count');

            Application::$app->session->setFlash('success', 'Login successfully');
            $response->redirect('/profile');
            return;
        } else if (!MyCaptcha::verifyResponse($body)){
            Application::$app->session->setFlash('error', 'Please verify captcha!');
        }

        // increase count variable to display captcha
        if (isset($body['submit'])){
            if (!Application::$app->cookie->get('count')){
                Application::$app->cookie->set('count', 1, 1);
            } else {
                $count = $_COOKIE['count'] + 1;
                Application::$app->cookie->set('count', $count, 1);
            }
        }

        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    // TODO: pass response through params
    public function register($request, $response){
        $user = new User();

        // post method
        // save data to user model
        $user->loadData($request->getBody());

        if ($user->validate() && $user->save()){
            Application::$app->session->setFlash('success', 'Thanks for registering');
            $response->redirect('/login');
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout($request, $response){
        // remove login session row from login_session table
        // TODO: check if login session exists, then remove it from DB
        $login_token = Application::$app->cookie->get('user') ?: Application::$app->session->get('user');
        if ($login_token){
            LoginSession::delete(['login_token' => $login_token]);
        }

        Application::$app->logout();
        $response->redirect('/login');
    }

    public function forgotPassword($request, $response){
        $forgot_form = new ForgotForm();

        $forgot_form->loadData($request->getBody());
        // send token to user email
        if ($forgot_form->validate() && $forgot_form->sendToken()){
            Application::$app->session->setFlash('success', 'Token has sent to your email');
            $response->redirect('/login');
            return;
        }
        $this->setLayout('auth');
        return $this->render('forgot', [
            'model' => $forgot_form
        ]);
    }

    public function resetPassword($request, $response){
        $reset_form = new ResetForm();

        $reset_form->loadData($request->getBody());
        // send token to user email
        if ($reset_form->validate() && $reset_form->resetPassword()){
            Application::$app->session->setFlash('success', 'Reset password successfully');
            $response->redirect('/login');
            return;
        }
        $this->setLayout('auth');
        return $this->render('reset', [
            'model' => $reset_form
        ]);
    }
}
