<?php

namespace core;

use core\db\Database;

class Application{
    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public Database $db;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Cookie $cookie;
    public static Application $app;
    public ?Controller $controller = null;
    public ?UserModel $user;
    public View $view;

    /**
     * @return Controller
     */
    public function getController(): Controller{
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void{
        $this->controller = $controller;
    }

    public function __construct($rootPath, array $config){
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

        $primaryValue = $this->cookie->get('user');
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
        } catch (\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exceptions' => $e
            ]);
        }
    }

    public function login(UserModel $user){
        // save user into session
        $this->user = $user;

        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->cookie->set('user', $primaryValue, $_ENV['COOKIE_EXPIRES']);
        return true;
    }

    public function logout(){
        $this->user = null;
        $this->cookie->remove('user');
    }
}