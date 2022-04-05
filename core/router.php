<?php

namespace core;

use core\exceptions\NotFoundException;

class Router{
    public $request;
    public $response;
    protected $routes = [];

    /** @var $middlewares \core\middlewares\BaseMiddleware */
    public $middlewares = [];

    /**
     * e.g: @var $routes = [
     *      'get' => [
     *          '/' => callback,
     *          '/contact' => callback
     *       ],
     *       'post' => ...
     * */
    public function get($path, $callback, $middlewares = []){
        $this->routes['get'][$path] = $callback;

        // Get the right middleware
        if ($path == Application::$app->request->getPath()
            && !empty($middlewares)
            && Application::$app->request->method() === 'get'){
            $this->middlewares = $middlewares;
        }
    }

    public function post($path, $callback, $middlewares = []){
        $this->routes['post'][$path] = $callback;

        // Get the right middleware
        if ($path == Application::$app->request->getPath()
            && !empty($middlewares)
            && Application::$app->request->method() === 'post'){
            $this->middlewares = $middlewares;
        }
    }


    /**
     * @param Request $request
     */
    public function __construct($request, $response){
        $this->request = $request;
        $this->response = $response;
    }

    public function resolve(){
        $path = $this->request->getPath();
        $method = $this->request->method();


        $callback = $this->routes[$method][$path] ?? false;

        if ($callback == false){
            $this->response->setStatusCode(404);
            throw new NotFoundException();
        }

        if (is_array($callback)){
            // e.g of callback: [AuthController::class, 'profile'] -> [controller, 'action']

            /** @var $controller \core\Controller
             */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            // execute middleware
            /** @var $middleware \core\middlewares\BaseMiddleware */
            if (!empty($this->middlewares)){
                foreach ($this->middlewares as $middleware){
                    $middleware->execute();
                }
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}