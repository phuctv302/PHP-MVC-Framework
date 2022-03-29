<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\Request;
use core\Response;
use models\ContactForm;
use models\User;

class SiteController extends Controller{
    public function home(){
        $currentUser = Application::$app->user ?? false;

        $params = [
            'user' => $currentUser
        ];
        return $this->render('home', $params);
    }
}
