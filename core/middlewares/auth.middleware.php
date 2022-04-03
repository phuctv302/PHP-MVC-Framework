<?php

namespace core\middlewares;

use core\Application;
use core\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware{
    public $actions = [];

    public function __construct($actions = []){
        $this->actions = $actions;
    }

    /**
     * @throws ForbiddenException
     */
    public function execute(){
        // not logged in yet
        if (Application::isGuest()){
            // actions is empty means all routes are restricted to access
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)){
                // TODO: Redirect to login
                throw new ForbiddenException();
            }
        }
    }
}
