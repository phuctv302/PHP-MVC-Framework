<?php

namespace core;

use core\middlewares\Basemiddleware;

class Controller{
    public string $layout = 'main';
    public string $action = '';

    protected array $middlewares = [];

    public function setLayout($layout){
        $this->layout = $layout;
    }

    public function render($view, $params = []){
        return Application::$app->view->renderView($view, $params);
    }

    public function registerMiddleware(Basemiddleware $middleware){
        $this->middlewares[] = $middleware;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array{
        return $this->middlewares;
    }


}
