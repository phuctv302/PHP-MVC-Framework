<?php

namespace controllers;

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
        if ($login_form->validate() && User::login($login_form) && MyCaptcha::verifyResponse($body['g-recaptcha-response'])){
            $user = $this->getUser();

            // create new login session and save it to database
            $login_session = LoginSession::create($user);
            $login_session->save();
            $login_token = $login_session->login_token;

            // remember me checkbox
            if (!empty($login_form->save_auth) && $login_form->save_auth == 'on'){
                $this->getCookie()->set('user', $login_token);
            } else if (empty($login_form->save_auth)){
                $this->getSession()->set('user', $login_token);
            }

            // reset captcha
            $this->getCookie()->remove('count');

            $this->getSession()->setFlash('success', 'Login successfully');
            $response->redirect('/profile');
        }

        // USER NOT CHECK CAPTCHA
        if (!MyCaptcha::verifyResponse($body['g-recaptcha-response'] ?? null)){
            $this->getSession()->setFlash(
                'error', 'Please verify captcha!');
        }
        MyCaptcha::increaseCounter($body, $this->getCookie());
        $this->setLayout('auth');
        return $this->render('login', [
            'model' => $login_form
        ]);
    }

    public function register($request, $response){
        $user = new User();

        // save data to user model
        $user->loadData($request->getBody());

        // check user existed with input email
        if ($this->isExisted($user->email, User::class, 'email')){
            $this->getSession()->setFlash(
                'error', 'User with this email has already existed!'
            );
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        // validate form error
        if (!$user->validate()){
            $this->getSession()->setFlash(
                'error', 'Oops! Invalid input data');
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        // save to db error => if no error: save to db
        if (!$user->save()){
            $this->getSession()->setFlash(
                'error', 'Error signing up user. Please try again later!');
            $this->setLayout('auth');
            return $this->render('register', [
                'model' => $user
            ]);
        }

        // ON SUCCESS
        $this->getSession()->setFlash('success', 'Thanks for registering');
        $response->redirect('/login');
    }

    public function logout($request, $response){
        // remove login session row from login_session table
        $login_token = $this->getCookie()->get('user') ?: $this->getSession()->get('user');
        if ($login_token){
            LoginSession::delete(['login_token' => $login_token]);
        }

        $this->setUser(null);
        $this->getCookie()->remove('user');
        $this->getSession()->remove('user');

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
        User::updateOne(['email' => $forgot_form->email], ['reset_token' => hash('sha256', $reset_token)]);

        // get url to reset password: reset?token=.....
        $url = $this->getUrl() . "/reset?token=$reset_token";

        // config email options
        $email = new Email($user);

        // message
        $message = $this->render('mails/reset.email', [
            'firstname' => $user->firstname,
            'url' => $url,
        ]);

        // send mails
        return $email->sendPasswordReset($message);
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
            $this->getSession()->setFlash('success', 'Token has sent to your email');
            $response->redirect('/login');
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
        $user = User::findOne(['reset_token' => hash('sha256', $reset_token)]);
        if (!$user){
           $this->getSession()->setFlash(
                'error', 'Reset token is invalid!'
            );
            $this->setLayout('auth');
            return $this->render('reset', [
                'model' => $reset_form
            ]);
        }

        // ON ERROR
        if (!$reset_form->validate()){
            $this->getSession()->setFlash(
                'error', 'Oops! Invalid input data!');
            $this->setLayout('auth');
            return $this->render('reset', [
                'model' => $reset_form
            ]);
        }

        // ON SUCCESS => redirect to login form
        // updating password and set reset_token = NULL
        User::updateOne(['id' => $user->id],
            ['password' => password_hash($reset_form->password, PASSWORD_DEFAULT),
                'reset_token' => NULL]);
        $this->getSession()->setFlash(
            'success', 'Reset password successfully!'
        );
        $response->redirect('/login');
    }
}
