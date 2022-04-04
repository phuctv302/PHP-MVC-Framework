<?php

namespace core\db;

use core\Application;
use core\Model;
use core\Session;
use models\User;
use services\ImageUploadService;

abstract class DbModel extends Model{
    abstract public static function tableName();

    abstract public function attributes(); // return all database columns name

    abstract public static function primaryKey();

    public function save(){
        $table_name = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(function($attr) { return ":$attr"; }, $attributes);
        $statement = self::prepare("INSERT INTO $table_name (" . implode(',', $attributes) . ")
            VALUES(" . implode(',', $params) . ")");

        foreach ($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    // find one method
    // where: eg. [email => phuc@example.com, firstname => phuc]
    // static -> object calling this method
    // e.g: ['email' => 'phuc@example.com']
    public static function findOne($where){
        $table_name = static::tableName(); // users
        $attributes = array_keys($where); // ['email']
        $sql = implode(" AND ", array_map(function($attr){
            if ($attr == 'expired_at') return "$attr > :$attr";
            return "$attr = :$attr";
            }, $attributes)); // 'email = :email'

        // SELECT * FROM $users WHERE email = :email [AND firstname = :firstname]
        $statement = self::prepare("SELECT * FROM $table_name WHERE $sql");
        foreach ($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

//        var_dump($statement);
//        exit;

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function updateOne($where, $updateData){
        $table_name = static::tableName();

        // for finding user
        $attribute = array_keys($where);
        $sql = implode(" AND ", array_map(function($attr){ return "$attr = :$attr"; }, $attribute));

        // for updating user
        $updateAttributes = array_keys($updateData);
        $updateSql = implode(",", array_map(function($attr){ return "$attr = :$attr"; }, $updateAttributes));


        // UPDATE users SET firstname="Hoang", lastname="Van" WHERE email="phuc@example.com"
        $statement = self::prepare("
            UPDATE $table_name
            SET $updateSql
            WHERE $sql
            ");

        // FOR UPDATING IMAGE
        $uploadedFile = (new ImageUploadService)->upload();

        // Bind value
        foreach ($where as $key => $value){
            $statement->bindValue(":$key", $value);
        }

        if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])){
            $updateData['photo'] = $uploadedFile ?: Application::$app->user->photo;
        }
        foreach ($updateData as $key => $value){
            $statement->bindValue(":$key", $value);
        }

//        var_dump($statement);
//        exit;

        // execute statement
        $statement->execute();

        return true;
    }

    // Delete all record matching the condition
    public static function deleteOne($where){
        $table_name = static::tableName();

        $attribute = array_keys($where);

        $sql = $sql = implode("AND", array_map(function($attr){ return "$attr = :$attr"; }, $attribute));

        $statement = self::prepare("
            DELETE FROM $table_name
            WHERE $sql
        ");

        // Bind value
        foreach ($where as $key => $value){
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();
    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);

    }
}
