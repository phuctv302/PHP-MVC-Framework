<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\middlewares\AuthMiddleware;
use core\Request;
use core\Response;
use models\EditForm;
use models\ForgotForm;
use models\LoginForm;
use models\ResetForm;
use models\User;

class AuthController extends Controller {
    public function __construct(){
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response){
        $login_form = new LoginForm();
        if ($request->isPost()){
            $login_form->loadData($request->getBody());
            if ($login_form->validate() && $login_form->login()){
                $response->redirect('/profile');
                return;
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
        if ($request->isPost()){
            // save data to user model
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()){
                // Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/login');
                exit;
            }
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        // Get method
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response){
        Application::$app->logout();
        $response->redirect('/login');
    }

    public function forgot(Request $request){
        $forgot_form = new ForgotForm();

        if ($request->isPost()){
            $forgot_form->loadData($request->getBody());
            // send token to user email
            if ($forgot_form->validate() && $forgot_form->sendToken()){
                Application::$app->response->redirect('/login');
                return;
            }
            $this->setLayout('auth');
            return $this->render('forgot', [
                'model' => $forgot_form
            ]);
        }

        $this->setLayout('auth');
        return $this->render('forgot', [
            'model' => $forgot_form
        ]);
    }

    public function reset(Request $request){
        $reset_form = new ResetForm();

        if ($request->isPost()){
            $reset_form->loadData($request->getBody());
            // send token to user email
            if ($reset_form->validate() && $reset_form->resetPassword()){
                Application::$app->response->redirect('/login');
                return;
            }
            $this->setLayout('auth');
            return $this->render('reset', [
                'model' => $reset_form
            ]);
        }

        $this->setLayout('auth');
        return $this->render('reset', [
            'model' => $reset_form
        ]);
    }

    public function updateUser(Request $request){
        $edit_form = new EditForm();
        if ($request->isPost()){
            $edit_form->loadData($request->getBody());
            if ($edit_form->validate() && $edit_form->updateUser($request->getBody())){
                Application::$app->response->redirect('/profile');
                return;
            }
            $this->setLayout('main');
            return $this->render('profile', [
                'model' => Application::$app->user
            ]);
        }
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => Application::$app->user
        ]);
    }

    public function profile(){
        return $this->render('profile', [
            'user' => Application::$app->user
        ]);
    }
}
