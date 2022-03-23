<?php
namespace controllers;

use core\Application;
use core\Controller;
use core\Request;
use core\Response;
use models\Loginform;
use models\User;

class AuthController extends Controller {
    public function login(Request $request, Response $response){
        $login_form = new Loginform();
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
        if ($request->isPost()){
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()){
                Application::$app->session->setFlash('success', 'Thanks for registering');
                Application::$app->response->redirect('/');
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

    public function logout(Request $request, Response  $response){
        Application::$app->logout();
        $response->redirect('/');
    }
}
