<?php

namespace core;

use core\db\Database;
use Exception;
use models\LoginSession;
use models\User;
use utils\DateConverter;

class Application{
    public static $ROOT_DIR;

    public static $app;

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

        $login_token = $this->cookie->get('user') ?: $this->session->get('user');
        if ($login_token){
            $login_session = LoginSession::findOne(
                ['login_token' => $login_token, 'expired_at' => DateConverter::toDate(time())]
            );

            $user = User::findOne(['id' => $login_session->user_id]);

            $this->user = $user;
        } else {
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

        return true;
    }

    public function logout(){
        $this->user = null;
        $this->cookie->remove('user');
        $this->session->remove('user');
    }
}