<?php

namespace controllers;

use core\Application;
use core\Controller;
use services\Email;
use services\MyCaptcha;
use forms\ForgotForm;
use forms\LoginForm;
use models\LoginSession;
use forms\ResetForm;
use models\User;
use utils\TokenGenerator;

/** @var $request \core\Request
 * @var $response \core\Response
 */
class AuthController extends Controller {

    // LOGIN FUNCTION
    public function login($request, $response){
        $login_form = new LoginForm();
        $body = $request->getBody();

        $login_form->loadData($body);

        // ON SUCCESS
        if ($login_form->validate() && LoginSession::login($login_form) && MyCaptcha::verifyResponse($body)){
            $user = Application::$app->user;

            // create new login session and save it to database
            $login_session = LoginSession::createNewLoginSesison($user);
            $login_session->save();
            $login_token = $login_session->login_token;

            // TODO: get post data from request obj
            // if user check remember me checkbox
            if (isset($body['save-auth']) && $body['save-auth'] == 'on'){
                Application::$app->cookie->set('user', $login_token, $_ENV['COOKIE_EXPIRES']);
            } else if (!isset($body['save-auth'])){
                Application::$app->session->set('user', $login_token);
            }

            // reset captcha
            MyCaptcha::reset();

            Application::$app->session->setFlash('success', 'Login successfully');
            $response->redirect('/profile');
            exit;
        } else if (!$login_form->validate()){
            Application::$app->session->setFlash(
                'error', 'Oops! Invalid input data');
        } else if (!MyCaptcha::verifyResponse($body)){
            Application::$app->session->setFlash(
                'error', 'Please verify captcha!');
        }
        MyCaptcha::increaseCounter($body);
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    // SIGN UP FUNCTION
    public function isExisted($value, $class_name, $unique_attr){
        $table_name = $class_name::tableName();
        $statement = Application::$app->db->prepare(
            "SELECT * FROM $table_name WHERE $unique_attr = :attr"
        );

        $statement->bindValue(":attr", $value);
        $statement->execute();
        $record = $statement->fetchObject();
        if ($record){
            return true; // true means this record has already existed in table
        }
        return false;
    }

    public function register($request, $response){
        $user = new User();

        // save data to user model
        $user->loadData($request->getBody());

        if ($this->isExisted($user->email, User::class, 'email')){
            Application::$app->session->setFlash(
                'error', 'User with this email has already existed!'
            );
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }
        if (!$user->validate()){
            Application::$app->session->setFlash(
                'error', 'Oops! Invalid input data');
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }
        if (!$user->save()){
            Application::$app->session->setFlash(
                'error', 'Error signing up user. Please try again later!');
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        // ON SUCCESS
        Application::$app->session->setFlash('success', 'Thanks for registering');
        $response->redirect('/login');
        exit;
        //        $this->setLayout('auth');
        //        return $this->render('register', [
        //            'model' => $user
        //        ]);
    }

    public function logout($request, $response){
        // remove login session row from login_session table
        // TODO: check if login session exists, then remove it from DB
        $login_token = Application::$app->cookie->get('user') ?: Application::$app->session->get('user');
        if ($login_token){
            LoginSession::delete(['login_token' => $login_token]);
        }

        Application::$app->logout();
        $response->redirect('/login');
    }

    // FORGOT PASSWORD FUNCTION
    public function sendToken($forgot_form){
        // find user with input email
        $user = User::findOne(['email' => $forgot_form->email]);
        if (!$user){
            $forgot_form->addError('email', 'User does not exist with this email');
            return false;
        }

        // create a random reset token
        $reset_token = TokenGenerator::signToken();
        User::updateOne(['email' => $forgot_form->email], ['reset_token' => $reset_token]);

        // get url to reset password: reset?token=.....
        $url = $this->getUrl() . "/reset?token=$reset_token";

        // config email options
        $email = new Email($user, $url);

        // send mail
        return $email->sendPasswordReset();
    }

    public function getUrl(){
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'http') === 0 ? 'http' : 'https';
        $host = $_SERVER['SERVER_NAME'];
        return $protocol . '://' . $host;
    }

    public function forgotPassword($request, $response){
        $forgot_form = new ForgotForm();

        $forgot_form->loadData($request->getBody());
        // send token to user email
        if ($forgot_form->validate() && $this->sendToken($forgot_form)){
            Application::$app->session->setFlash('success', 'Token has sent to your email');
            $response->redirect('/login');
            return;
        }
        $this->setLayout('auth');
        return $this->render('forgot', [
            'model' => $forgot_form
        ]);
    }

    public function resetPassword($request, $response){
        $reset_form = new ResetForm();

        $reset_form->loadData($request->getBody());

        // get reset token from url
        $reset_token = explode('=', $_SERVER['REQUEST_URI'], 2)[1];

        // find user with that token
        $user = User::findOne(['reset_token' => $reset_token]);
        if (!$user){
            Application::$app->session->setFlash(
                'error', 'Reset token is invalid!'
            );
            return false;
        }

        // ON ERROR
        if (!$reset_form->validate()){
            Application::$app->session->setFlash(
                'error', 'Oops! Invalid input data!');
            $this->setLayout('auth');
            return $this->render('reset', [
                'model' => $reset_form
            ]);
        }

        // ON SUCCESS
        // updating
        User::updateOne(['id' => $user->id],
            ['password' => password_hash($reset_form->password, PASSWORD_DEFAULT),
                'reset_token' => NULL]);
        Application::$app->session->setFlash(
            'success', 'Reset password successfully!'
        );
        $response->redirect('/login');
        exit;
    }
}
