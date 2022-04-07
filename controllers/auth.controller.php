<?php

namespace controllers;

use core\Controller;
use forms\ForgotForm;
use forms\LoginForm;
use forms\ResetForm;
use models\LoginSession;
use models\User;
use services\Email;
use services\MyCaptcha;
use utils\TokenGenerator;

/**
 * authentication operations
 * get request and send response to client
 * */
class AuthController extends Controller {

    /**
     * increase count variable for displaying captcha (>=3)
     * @param array $body is body from request
     **/
    private function increaseCounter($body){
        if (isset($body['submit'])){
            if (!$this->getCookie()->get('count')){
                // init
                $this->getCookie()->setForCaptcha('count', 1);
            } else {
                // increase
                $count = $_COOKIE['count'] + 1;
                $this->getCookie()->setForCaptcha('count', $count);
            }
        }
    }

    /**
     * Log user in, captcha, @redirect to profile
     * @param \core\Request $request
     * @param \core\Response $response
     * */
    public function login($request, $response){
        $login_form = new LoginForm();
        $body = $request->getBody();

        $login_form->loadData($body);

        // ERROR validating
        if (!$login_form->validate()){
            $this->getSession()->setFlash(
                'error', 'Oops! Invalid input data.');

            $this->increaseCounter($body);

            $this->setLayout('auth');
            return $this->render('login', [
                'model' => $login_form
            ]);
        }

        // check error logging in, no error => login and save user to Application
        if (!User::login($login_form)){
            $this->getSession()->setFlash(
                'error', 'Error logging user in!');

            $this->increaseCounter($body);

            $this->setLayout('auth');
            return $this->render('login', [
                'model' => $login_form
            ]);
        }

        if (!MyCaptcha::verifyResponse($body['g-recaptcha-response'])){
            $this->getSession()->setFlash(
                'error', 'Please verify captcha!');

            $this->increaseCounter($body);

            $this->setLayout('auth');
            return $this->render('login', [
                'model' => $login_form
            ]);
        }

        // ON SUCCESS
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

    /**
     * Sign up new user
     * @redirect to login
     * @param \core\Request $request
     * @param \core\Response $response
     * */
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

    /**
     * This method is for forgotPassword function
     * create token and send email with that token
     * @return true and send email if send successfully,
     * otherwise @return false
     * */
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
        return $email->send($message, 'Your password reset token');
    }

    // Get url for directing to reset password form
    private function getUrl(){
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'http') === 0 ? 'http' : 'https';
        $host = $_SERVER['SERVER_NAME'];
        return $protocol . '://' . $host;
    }

    /**
     * Actual send email with reset token to user
     * @redirect to login
     * @param  \core\Request $request
     * @param \core\Response $response
     * */
    public function forgotPassword($request, $response){
        $forgot_form = new ForgotForm();

        $forgot_form->loadData($request->getBody());
        // send token to user email

        // ERROR validating form
        if (!$forgot_form->validate()){
            $this->getSession()->setFlash('error', 'Oops! Invalid input data.');

            $this->setLayout('auth');
            return $this->render('forgot', [
                'model' => $forgot_form
            ]);
        }

        // ERROR sending email
        if (!$this->sendToken($forgot_form)){
            $this->getSession()->setFlash('error', 'Error sending email! Please try again later.');

            $this->setLayout('auth');
            return $this->render('forgot', [
                'model' => $forgot_form
            ]);
        }

        // ON SUCCESS
        $this->getSession()->setFlash('success', 'Token has sent to your email');
        $response->redirect('/login');
    }

    /**
     * Update user password and set @var $reset_token = @NULL
     * */
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

        // ON ERROR validating form
        if (!$reset_form->validate()){
            $this->getSession()->setFlash(
                'error', 'Oops! Invalid input data!');
            $this->setLayout('auth');
            return $this->render('reset', [
                'model' => $reset_form
            ]);
        }

        // ERROR updating password; no error => update
        if (!User::updateOne(['id' => $user->id],
            ['password' => password_hash($reset_form->password, PASSWORD_DEFAULT),
                'reset_token' => NULL])){
            $this->getSession()->setFlash(
                'error', 'Error resetting password!');

            $this->setLayout('auth');
            return $this->render('reset', [
                'model' => $reset_form
            ]);
        }

        // ON SUCCESS => redirect to login form
        // updating password and set reset_token = NULL
        $this->getSession()->setFlash(
            'success', 'Reset password successfully!'
        );
        $response->redirect('/login');
    }
}
