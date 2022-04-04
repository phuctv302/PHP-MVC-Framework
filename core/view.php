<?php

namespace core;

class View {
    public $title = '';

    public function renderView($view, $params = []){
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent(){

        // default layout is 'main'
        $layout = 'main';
        if (Application::$app->controller){
            $layout = Application::$app->controller->layout;
        }
        ob_start(); // caching the output
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean(); // return output & clear the buffer
    }

    protected function renderOnlyView($view, $params){
        foreach ($params as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
