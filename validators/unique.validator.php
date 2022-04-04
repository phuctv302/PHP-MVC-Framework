<?php

namespace validators;

use core\Application;
use core\Validator;

class UniqueValidator implements Validator {

    public static function validate($value, $rule, $model, $attribute){
        $class_name = $rule['class'];
        $uniqueAttr = $rule['attribute'] ?? $attribute;
        $table_name = $class_name::tableName();
        $statement = Application::$app->db->prepare(
            "SELECT * FROM $table_name WHERE $uniqueAttr = :attr"
        );

        $statement->bindValue(":attr", $value);
        $statement->execute();
        $record = $statement->fetchObject();
        if ($record){
            return true; // true means this record has already existed in table
        }
        return false;
    }
}