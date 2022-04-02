<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\middlewares\AuthMiddleware;
use core\MyCaptcha;
use core\Request;
use core\Response;
use models\EditForm;
use models\ForgotForm;
use models\ImageForm;
use models\LoginForm;
use models\ResetForm;
use models\User;

class AuthController extends Controller {
    public function __construct(){
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response){
        $login_form = new LoginForm();
        $myCaptcha = new MyCaptcha();
        $login_form->loadData($request->getBody());
        if ($login_form->validate() && $login_form->login() && $myCaptcha->verifyResponse()){
            Application::$app->session->setFlash('success', 'Login successfully');
            $response->redirect('/profile');
            return;
        } else if (!$myCaptcha->verifyResponse()){
            Application::$app->session->setFlash('error', 'Please verify captcha!');
        }

        // increase count variable to display captcha
        if (isset($_POST['submit'])){
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

    public function register(Request $request){
        $user = new User();

        // post method
        // save data to user model
        $user->loadData($request->getBody());

        if ($user->validate() && $user->save()){
            Application::$app->session->setFlash('success', 'Thanks for registering');
            Application::$app->response->redirect('/login');
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response){
        Application::$app->logout();
        $response->redirect('/login');
    }

    public function forgotPassword(Request $request){
        $forgot_form = new ForgotForm();

        $forgot_form->loadData($request->getBody());
        // send token to user email
        if ($forgot_form->validate() && $forgot_form->sendToken()){
            Application::$app->session->setFlash('success', 'Token has sent to your email');
            Application::$app->response->redirect('/login');
            return;
        }
        $this->setLayout('auth');
        return $this->render('forgot', [
            'model' => $forgot_form
        ]);
    }

    public function resetPassword(Request $request){
        $reset_form = new ResetForm();

        $reset_form->loadData($request->getBody());
        // send token to user email
        if ($reset_form->validate() && $reset_form->resetPassword()){
            Application::$app->session->setFlash('success', 'Reset password successfully');
            Application::$app->response->redirect('/login');
            return;
        }
        $this->setLayout('auth');
        return $this->render('reset', [
            'model' => $reset_form
        ]);
    }
}
