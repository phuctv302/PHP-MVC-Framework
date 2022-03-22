<?php
namespace controllers;

use core\Controller;
use core\Request;
use models\Registermodel;

class AuthController extends Controller {
    public function login(){
        $this->setLayout('auth');
        return $this->render('login');
    }

    public function register(Request $request){
        $registerModel = new Registermodel();
        if ($request->isPost()){
            $registerModel->loadData($request->getBody());

            if ($registerModel->validate() && $registerModel->register()){
                return 'Success';
            }

            echo '<pre>';
            var_dump($registerModel->errors);
            echo '</pre>';

            return $this->render('register', [
                'model' => $registerModel
            ]);
        }
        $this->setLayout('auth');
        return $this->render('register', [
            'model' => $registerModel
        ]);
    }
}
