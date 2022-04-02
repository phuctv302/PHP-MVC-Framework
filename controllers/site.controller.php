<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\MyCaptcha;
use models\ForgotForm;
use models\LoginForm;
use models\ResetForm;
use models\User;

class SiteController extends Controller{
    public function home(){
        $currentUser = Application::$app->user ?? false;

        $params = [
            'user' => $currentUser
        ];
        return $this->render('home', $params);
    }

    public function userUpdateForm(){
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => Application::$app->user,
            'user' => Application::$app->user,
        ]);
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
