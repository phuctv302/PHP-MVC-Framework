<?php
namespace controllers;

use core\Application;
use core\Controller;
use core\Request;
use models\User;

class AuthController extends Controller {
    public function login(){
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request){
        $user = new User();
        if ($request->isPost()){
            $user->loadData($request->getBody());

            if ($user->validate() && $user->save()){

                Application::$app->response->redirect('/');
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
}
