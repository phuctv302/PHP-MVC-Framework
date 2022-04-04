<?php

namespace core;

class Controller {
    public $layout = 'main';
    public $action = '';

    public function setLayout($layout){
        $this->layout = $layout;
    }

    public function render($view, $params = []){
        return Application::$app->view->renderView($view, $params);
    }
}
