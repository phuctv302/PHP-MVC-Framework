<?php

namespace core;

use core\db\Database;

class Application{
    // for event
    const EVENT_BEFORE_REQUEST = 'beforeRequest';
    const EVENT_AFTER_REQUEST = 'afterRequest';
    protected array $event_listeners = [];

    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public Database $db;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public static Application $app;
    public ?Controller $controller = null;
    public ?Usermodel $user;
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

    public function __construct($rootPath, $config){
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');
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
        $this->triggerEvent(self::EVENT_BEFORE_REQUEST);
        try{
            echo $this->router->resolve();
        } catch (\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renderView('_error', [
                'exception' => $e
            ]);
        }
    }

    public function login(Usermodel $user){
        // save user into session
        $this->user = $user;

        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }

    public function logout(){
        $this->user = null;
        $this->session->remove('user');
    }

    public function triggerEvent($event_name){
        $callbacks = $this->event_listeners[$event_name] ?? [];
        foreach ($callbacks as $callback){
            call_user_func($callback);
        }
    }

    public function on($event_name, $callback){
        $this->event_listeners[$event_name][] = $callback;
    }
}