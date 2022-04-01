<?php

namespace core;

use core\middlewares\BaseMiddleware;

class Controller{
    public $layout = 'main';
    public $action = '';

    protected $middlewares = [];

    public function setLayout($layout){
        $this->layout = $layout;
    }

    public function render($view, $params = []){
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware($middleware){
        $this->middlewares[] = $middleware;
    }

    /**
     * @return array
     */
    public function getMiddlewares(){
        return $this->middlewares;
    }


}
