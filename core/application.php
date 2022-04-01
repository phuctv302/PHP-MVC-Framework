<?php

namespace core;

use core\db\Database;
use Exception;

class Application{
    public static $ROOT_DIR;

    public $layout = 'main';
    public $userClass;
    public $db;
    public $router;
    public $request;
    public $response;
    public $session;
    public $cookie;
    public static $app;
    public $controller = null;
    public $user;
    public $view;

    /**
     * @return Controller
     */
    public function getController(){
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController($controller){
        $this->controller = $controller;
    }

    public function __construct($rootPath, $config){
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        $primaryValue = $this->cookie->get('user') ?: $this->session->get('user');
        if ($primaryValue){
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else{
            $this->user = null;
        }
    }

    public static function isGuest(){
        return !self::$app->user;
    }

    public function run(){
        try{
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

        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};

        // if user check remember me checkbox
        if (isset($_POST['save-auth']) && $_POST['save-auth'] == 'on'){
            $this->cookie->set('user', $primaryValue, $_ENV['COOKIE_EXPIRES']);
        } else if (!isset($_POST['save-auth'])){
            $this->session->set('user', $primaryValue);
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