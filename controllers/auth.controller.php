<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\middlewares\AuthMiddleware;
use core\Request;
use core\Response;
use models\EditForm;
use models\LoginForm;
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
                $response->redirect('/');
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

            return $this->render('register', [
                'model' => $user
            ]);
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $user
        ]);
    }

    public function logout(Request $request, Response $response){
        Application::$app->logout();
        $response->redirect('/');
    }

    public function updateUser(Request $request){
        $edit_form = new EditForm();
        if ($request->isPost()){
            $edit_form->loadData($request->getBody());
            if ($edit_form->validate() && $edit_form->updateUser($request->getBody())){
                Application::$app->response->redirect('/profile');
                return;
            }
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
