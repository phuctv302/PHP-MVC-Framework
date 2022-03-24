<?php
namespace core\middlewares;

use core\Application;
use core\exception\Forbiddenexception;

class Authmiddleware extends Basemiddleware {
    public array $actions = [];

    public function __construct(array $actions = []){
        $this->actions = $actions;
    }

    /**
     * @throws Forbiddenexception
     */
    public function execute(){
        if (Application::isGuest()){
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                throw new Forbiddenexception();
            }
        }
    }
}
