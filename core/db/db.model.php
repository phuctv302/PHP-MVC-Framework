<?php

namespace core\db;

use core\Application;
use core\Model;
use models\User;

abstract class DbModel extends Model{
    abstract public static function tableName(): string;

    abstract public function attributes(): array; // return all database columns name

    abstract public static function primaryKey(): string;

    public function save(){
        $table_name = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
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
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes)); // 'email = :email'

        // SELECT * FROM $users WHERE email = :email [AND firstname = :firstname]
        $statement = self::prepare("SELECT * FROM $table_name WHERE $sql");
        foreach ($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function updateOne($where, $updateData){
        $table_name = static::tableName();

        // for finding user
        $attribute = array_keys($where);
        $sql = implode("AND", array_map(fn($attr) => "$attr = :$attr", $attribute));

        // for updating user
        $updateAttributes = array_keys($updateData);
        $updateSql = implode(",", array_map(fn($attr) => "$attr = :$attr", $updateAttributes));

        // UPDATE users SET firstname="Hoang", lastname="Van" WHERE email="phuc@example.com"
        $statement = self::prepare("
            UPDATE $table_name
            SET $updateSql
            WHERE $sql
            ");


        // Bind value
        foreach ($where as $key => $value){
            $statement->bindValue("$key", $value);
        }

        foreach ($updateData as $key => $value){
            $statement->bindValue(":$key", $value);
        }

        // execute statement
        $statement->execute();
//        return self::findOne($where);
    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);

    }
}
