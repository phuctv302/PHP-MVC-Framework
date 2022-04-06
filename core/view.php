<?php

namespace core;

// Render view and layout to client
class View {
    public $title = '';

    /**
     * @return string view & layout
     * @param string $view filename containing the view we want to render
     * @param array $params variables can be used in view
     * */
    public function render($view, $params = []){
        // set the view first then layout => use $this->title
        $viewContent = $this->renderView($view, $params);
        $layoutContent = $this->layoutContent();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /** @return string layout */
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

    /** @return string view
     *  @param string $view filename containing view
     *  @param array $params
     */
    protected function renderView($view, $params){
        foreach ($params as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
