<?php

namespace core\db;

use core\Application;
use core\Model;
use core\Session;
use models\User;

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
        $sql = implode("AND ", array_map(function($attr){ return "$attr = :$attr"; }, $attributes)); // 'email = :email'

        // SELECT * FROM $users WHERE email = :email [AND firstname = :firstname]
        $statement = self::prepare("SELECT * FROM $table_name WHERE $sql");
        foreach ($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function updateOne($where, $updateData){
        // TODO: put it to Middlewares
        // CHECK CSRF ATTACK
        if (!isset($_POST[Session::CSRF_TOKEN_KEY])
            || $_POST[Session::CSRF_TOKEN_KEY] != $_SESSION[Session::CSRF_TOKEN_KEY]){
            Application::$app->session->setFlash('error', 'CSRF Attacking! We\'re not gonna submit your form');
            return false;
        }

        $table_name = static::tableName();

        // for finding user
        $attribute = array_keys($where);
        $sql = implode("AND", array_map(function($attr){ return "$attr = :$attr"; }, $attribute));

        // for updating user
        $updateAttributes = array_keys($updateData);
        $updateSql = implode(",", array_map(function($attr){ return "$attr = :$attr"; }, $updateAttributes));


        // UPDATE users SET firstname="Hoang", lastname="Van" WHERE email="phuc@example.com"
        $statement = self::prepare("
            UPDATE $table_name
            SET $updateSql
            WHERE $sql
            ");

        // TODO: put it to ImageUploadService
        // FOR UPDATING IMAGE
        $uploadedFile = '';
        if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])){
            // check if user post image
            if (strpos($_FILES['photo']['type'], 'image') !== 0){
                Application::$app->session->setFlash('error', 'Please choose an image!');
                return false;
            } else {
                // copy image to public/img/users/user-{id}-{time()}
                $uploadedDir = Application::$ROOT_DIR . '\\public\\img\\users\\';
                $uploadedExt = '.' . explode('/', $_FILES['photo']['type'])[1];
                $uploadedFile = 'user-'
                    . Application::$app->cookie->get('user')
                    . '-'
                    . time()
                    . $uploadedExt;

                move_uploaded_file($_FILES['photo']['tmp_name'], $uploadedDir . $uploadedFile);
            }
        }


        // Bind value
        foreach ($where as $key => $value){
            $statement->bindValue(":$key", $value);
        }

        foreach ($updateData as $key => $value){

            if ($key == 'photo'){
                $value = $uploadedFile;
            }

            $statement->bindValue(":$key", $value);
        }

//        var_dump($statement);
//        exit;

        // execute statement
        $statement->execute();

        return true;
    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);

    }
}
