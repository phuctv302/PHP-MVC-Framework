<?php

namespace core\form;

use core\Model;

class Form {
    public static function begin($action, $method){
        echo sprintf('<form action="%s" method="%s">', $action, $method);
        return new form();
    }

    public static function end(){
        echo '</form>';
    }

    public function field(Model $model, $attribute){
        return new Inputfield($model, $attribute);
    }
}
