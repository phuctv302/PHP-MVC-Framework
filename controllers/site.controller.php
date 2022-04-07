<?php

namespace controllers;

use core\Controller;
use forms\ForgotForm;
use forms\LoginForm;
use forms\ResetForm;
use models\User;

/**
 * For get method
 * Render view (form) only
 * */
class SiteController extends Controller {

    /**
     * When user access "/"
     * @redirect to login if user has not logged in yet
     * otherwise @redirect to profile
     * @param  \core\Request $request
     * @param \core\Response $response
     * */
    public function home($request, $response){
        $currentUser = $this->getUser() ?? false;

        if (!$currentUser){
            $response->redirect('/login');
        } else {
            $response->redirect('/profile');
        }
    }

    /**
     * When user access "/profile"
     * */
    public function profile(){
        $this->setLayout('main');

        // form can not be empty => we have to pass user instead
        return $this->render('profile', [
            'user' => $this->getUser(),
            'model' => $this->getUser()
        ]);
    }

    /**
     * When user access "/login"
     * */
    public function loginForm(){
        $login_form = new LoginForm();

        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    /**
     * When user access "/register"
     * */
    public function registerForm(){
        $user = new User();

        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    /**
     * When user access "/forgot"
     * */
    public function forgotPasswordForm(){
        $forgot_form = new ForgotForm();

        $this->setLayout('auth');
        return $this->render('forgot', [
            'model' => $forgot_form
        ]);
    }

    /**
     * When user access "/reset"
     * */
    public function resetPasswordForm(){
        $reset_form = new ResetForm();

        $this->setLayout('auth');
        return $this->render('reset', [
            'model' => $reset_form
        ]);
    }
}
