<?php

namespace controllers;

use core\Application;
use core\Controller;

class SiteController extends Controller{
    public function home(){
        $currentUser = Application::$app->user ?? false;

        $params = [
            'user' => $currentUser
        ];
        return $this->render('home', $params);
    }
}
