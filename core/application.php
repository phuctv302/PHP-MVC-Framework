<?php

namespace core;

use core\db\Database;
use Exception;

/*
 * run app
 * */
class Application {
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

    /**
     * instantiate classes on core
     * @param string $rootPath is the root of this project
     * @param array $config configurations for connecting to database
     *
     * @throws Exception when connecting to database
     */
    public function __construct($rootPath, $config){
        self::$ROOT_DIR = $rootPath;

        // single ton => call all attribute of Application everywhere in this project
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->cookie = new Cookie();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

    }

    /**
     * call @function resolve() from router
     * @throws \Exception in exceptions package
     * */
    public function run(){
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            $this->response->setStatusCode($e->getCode());
            echo $this->view->render('_error', [
                'exception' => $e
            ]);
        }
    }
}