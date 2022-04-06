<?php

namespace core;

use core\db\Database;
use Exception;

class Application{
    public static $ROOT_DIR;

    public static $app;

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
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

    }

    public static function isGuest(){
        return !self::$app->user;
    }

    public function run(){
        try {
            echo $this->router->resolve();
        } catch (Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->render('_error', [
                'exception' => $e
            ]);
        }
    }
}