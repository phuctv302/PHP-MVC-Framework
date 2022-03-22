<?php

namespace controllers;

use core\Application;
use core\Controller;
use core\Request;

class Sitecontroller extends Controller{
    public function home() {
        $params = [
            'name' => "TheCodeholic"
        ];
        return $this->render('home', $params);
    }


    public function contact() {
        return $this->render('contact');
    }

    public function handleContact(Request $request){
        $body = $request->getBody();
        return 'Handling submitted data';
    }
}
