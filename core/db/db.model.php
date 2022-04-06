<?php

namespace core\db;

use core\Application;
use core\Model;
use services\ImageUploadService;

/**
 * abstract class
 * Database operations: find, update, delete, save
 * Class @extends $this is corresponding to a table in database
 * */
abstract class DbModel extends Model {

    /**
     * @return string table's name in database
     * */
    abstract public static function tableName();

    // return all column in database we want to save
    abstract public function attributes();

    abstract public static function primaryKey();

    /**
     * Save document into database
     * @return true if success
     * otherwise @return false
     * */
    public function save(){
        $table_name = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(function ($attr){
            return ":$attr";
        }, $attributes);
        $statement = self::prepare("INSERT INTO $table_name (" . implode(',', $attributes) . ")
            VALUES(" . implode(',', $params) . ")");

        foreach ($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    /**
     * find one row in table
     * @param array $where @example: [email => phuc@example.com, firstname => phuc]
     * @return object from table which @extends DbModel
     * otherwise @return false
     * */
    public static function findOne($where){
        // static: object calling this method
        $table_name = static::tableName(); // users
        $attributes = array_keys($where); // ['email']
        $sql = implode(" AND ", array_map(function ($attr){
            if ($attr == 'expired_at')
                return "$attr > :$attr";
            return "$attr = :$attr";
        }, $attributes)); // 'email = :email'

        // SELECT * FROM $users WHERE email = :email [AND firstname = :firstname]
        $statement = self::prepare("SELECT * FROM $table_name WHERE $sql");
        foreach ($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    /**
     * find one row in table
     * @param array $where @example: [email => phuc@example.com, firstname => phuc]
     * @param array $updateData @example; [firstname => 'Phuc']
     * @return true if success
     * otherwise @return false
     * */
    public static function updateOne($where, $updateData){
        $table_name = static::tableName();

        // for finding user
        $attribute = array_keys($where);
        $sql = implode(" AND ", array_map(function ($attr){
            return "$attr = :$attr";
        }, $attribute));

        // for updating user
        $updateAttributes = array_keys($updateData);
        $updateSql = implode(",", array_map(function ($attr){
            return "$attr = :$attr";
        }, $updateAttributes));


        // UPDATE users SET firstname="Hoang", lastname="Van" WHERE email="phuc@example.com"
        $statement = self::prepare("
            UPDATE $table_name
            SET $updateSql
            WHERE $sql
            ");


        // Bind value
        foreach ($where as $key => $value){
            $statement->bindValue(":$key", $value);
        }

        // FOR UPDATING IMAGE
        if (ImageUploadService::checkFileExist('photo')){
            $uploadedDir = Application::$ROOT_DIR . '\\public\\img\\users\\';
            $uploadedExt = '.' . explode('/', $_FILES['photo']['type'])[1];
            $uploadedFile = 'user-'
                . Application::$app->user->id
                . '-'
                . time()
                . $uploadedExt;

            if (!ImageUploadService::isImage('photo')){
                Application::$app->session->setFlash('error', 'Please choose an image!');
                return false;
            }
            ImageUploadService::upload('photo', $uploadedDir . $uploadedFile);
            $updateData['photo'] = $uploadedFile ?: Application::$app->user->photo;
        }
        foreach ($updateData as $key => $value){
            $statement->bindValue(":$key", $value);
        }

        // execute statement
        $statement->execute();

        return true;
    }

    /**
     * delete one row in table
     * @param array $where @example: [email => phuc@example.com, firstname => phuc]
     * */
    public static function delete($where){
        $table_name = static::tableName();

        $attribute = array_keys($where);

        $sql = implode("AND", array_map(function ($attr){
            return "$attr = :$attr";
        }, $attribute));

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

    /**
     * prepare sql statement before executing
     * @return false|\PDOStatement
     * */
    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);

    }

    // GET USER FROM APPLICATION
    /**
     * @return \models\User from Application
     * */
    public function getUser(){
        return Application::$app->user;
    }

    // GET APPLICATION
    public function getApp(){
        return Application::$app;
    }
}
