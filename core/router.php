<?php

namespace core;

use core\exceptions\NotFoundException;

class Router{
    public $request;
    public $response;
    protected $routes = [];

    /**
     * e.g: @var $routes = [
     *      'get' => [
     *          '/' => callback,
     *          '/contact' => callback
     *       ],
     *       'post' => ...
     * */
    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
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

        if (is_string($callback)){
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)){
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware){
                $middleware->execute();
            }
        }

        return call_user_func($callback, $this->request, $this->response);
    }
}