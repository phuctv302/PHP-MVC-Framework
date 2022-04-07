<?php

namespace core;

use core\exceptions\NotFoundException;

// Execute the callback and middleware
class Router {

    /** @var Request */
    public $request;

    /** @var Response */
    public $response;

    /** @var array contains controller and the action we want to execute */
    protected $callback;

    /** @var array \core\BaseMiddleware */
    public $middlewares = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct($request, $response){
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * e.g: @var $routes = [
     *      'get' => [
     *          '/' => [callback, middlewares]
     *          '/contact' => callback
     *       ],
     *       'post' => ...
     * */
    public function get($path, $callback, $middlewares = []){
        // Get the right middleware
        if ($path == $this->request->getPath() && $this->request->method() === 'get'){
            $this->callback = $callback;
            $this->middlewares = $middlewares;
        }
    }

    public function post($path, $callback, $middlewares = []){
        // Get the right middleware
        if ($path == $this->request->getPath() && $this->request->method() === 'post'){
            $this->callback = $callback;
            $this->middlewares = $middlewares;
        }
    }

    /**
     * execute the callback
     * @return string the result view or @redirect to a path
     * @throws NotFoundException if accessing non-existed url
     * will be @catched in @class Application
     */
    public function resolve(){

        if ($this->callback == false){
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }

        if (is_array($this->callback)){
            // e.g of callback: [AuthController::class, 'profile'] -> [controller, 'action']

            /** @var $controller \core\Controller
             */
            $controller = new $this->callback[0](); // instantiate for current Controller
            Application::$app->controller = $controller;
            // $controller->action = $this->callback[1]; // not needed
            $this->callback[0] = $controller;

            // execute middlewares
            /** @var $middleware \middlewares\AuthMiddleware */
            /** @var $middleware \middlewares\CsrfMiddleware */
            if (!empty($this->middlewares)){
                foreach ($this->middlewares as $middleware){
                    $middleware->execute();
                }
            }
        }

        return call_user_func($this->callback, $this->request, $this->response);
    }
}