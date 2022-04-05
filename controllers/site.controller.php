<?php

namespace controllers;

use core\Application;
use core\Controller;
use forms\ForgotForm;
use forms\LoginForm;
use forms\ResetForm;
use models\User;

class SiteController extends Controller{

    public function home($request, $response){
        $currentUser = Application::$app->user ?? false;

        if (!$currentUser){
            $response->redirect('/login');
        } else {
            $response->redirect('/profile');
        }
    }

    public function profile(){
        $this->setLayout('main');
        return $this->render('profile', [
            'user' => Application::$app->user,
            'model' => Application::$app->user
        ]);
    }

    public function loginForm(){
        $login_form = new LoginForm();

        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    public function registerForm(){
        $user = new User();

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function forgotPasswordForm(){
        $forgot_form = new ForgotForm();

        $this->setLayout('auth');
        return $this->render('forgot', [
            'model' => $forgot_form
        ]);
    }

    public function resetPasswordForm(){
        $reset_form = new ResetForm();

        $this->setLayout('auth');
        return $this->render('reset', [
            'model' => $reset_form
        ]);
    }
}
