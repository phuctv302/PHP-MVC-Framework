<?php

namespace core;

use core\db\Database;
use Exception;

class Application{
    public static $ROOT_DIR;

    public static $app;

    // TODO: controller should control layout
    public $user_class;
    public $db;
    public $router;
    public $request;
    public $response;
    public $session;
    public $cookie;
    public $controller = null;
    public $user;
    public $view;

    public function __construct($rootPath, $config){
        $this->user_class = $config['user_class'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        // TODO: token should be a random string more than 64 chars
        $primary_value = $this->cookie->get('user') ?: $this->session->get('user');
        if ($primary_value){
            $primary_key = $this->user_class::primaryKey();
            $this->user = $this->user_class::findOne([$primary_key => $primary_value]);
        } else{
            $this->user = null;
        }
    }

    public static function isGuest(){
        return !self::$app->user;
    }

    public function run(){
        try {
            echo $this->router->resolve();
        } catch (Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login($user){
        // save user into session
        $this->user = $user;

        $primary_key = $user->primaryKey();
        $primary_value = $user->{$primary_key};

        // TODO: should be in controller
        // if user check remember me checkbox
        if (isset($_POST['save-auth']) && $_POST['save-auth'] == 'on'){
            $this->cookie->set('user', $primary_value, $_ENV['COOKIE_EXPIRES']);
        } else if (!isset($_POST['save-auth'])){
            $this->session->set('user', $primary_value);
        }

        // reset captcha
        Application::$app->cookie->remove('count');

        return true;
    }

    public function logout(){
        $this->user = null;
        $this->cookie->remove('user');
    }
}