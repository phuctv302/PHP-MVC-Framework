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
        if ($request->isPost()){
            $login_form->loadData($request->getBody());
            if ($login_form->validate() && $login_form->login() && $myCaptcha->verifyResponse()){
                Application::$app->session->setFlash('success', 'Login successfully');
                $response->redirect('/profile');
                return;
            } else if(!$myCaptcha->verifyResponse()) {
                Application::$app->session->setFlash('error', 'Please verify captcha!');
            }

            // increase count variable to display captcha
            if (isset($_POST['submit'])){
                if (!Application::$app->cookie->get('count')) {
                    Application::$app->cookie->set('count', 1, 1);
                } else {
                    $count = $_COOKIE['count'] + 1;
                    Application::$app->cookie->set('count', $count, 1);
                }
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
                Application::$app->session->setFlash('success', 'Thanks for registering');
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
                Application::$app->session->setFlash('success', 'Token has sent to your email');
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
                Application::$app->session->setFlash('success', 'Reset password successfully');
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

            // if user not choose new photo
            if (empty($edit_form->photo)){
                $edit_form->photo = Application::$app->user->photo;
            }


            if ($edit_form->validate() && $edit_form->updateUser($request->getBody())){
                Application::$app->session->setFlash('success', 'Update data successfully');
                Application::$app->response->redirect('/profile');
                return;
            }

            $this->setLayout('main');
            return $this->render('profile', [
                'model' => $edit_form,
                'user' => Application::$app->user,
            ]);
        }
        $this->setLayout('main');
        return $this->render('profile', [
            'model' => Application::$app->user,
            'user' => Application::$app->user,
        ]);
    }

    public function updatePhoto(Request $request, Response $response){
        $image_form = new ImageForm();
        if (isset($_POST['photo']) && !empty($_POST['photo'])){
            $image_form->loadData($request->getBody());

            if ($image_form->validate() && $image_form->uploadUserPhoto($request->getBody())){
                Application::$app->session->setFlash('success', "Update photo successfully");
                $response->redirect('/profile');
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
        $this->setLayout('main');
        return $this->render('profile', [
            'user' => Application::$app->user,
            'model' => Application::$app->user
        ]);
    }
}
