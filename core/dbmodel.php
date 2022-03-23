<?php
namespace core;

abstract class Dbmodel extends Model {
    abstract public static function tableName(): string;

    abstract public function attributes(): array; // return all database columns name

    abstract public static function primaryKey(): string;

    public function save(){
        $table_name = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $table_name (".implode(',', $attributes).")
            VALUES(".implode(',', $params).")");

        foreach ($attributes as $attribute){
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    // find one method
    // where: eg. [email => phuc@example.com, firstname => phuc]
    public static function findOne($where){
        $table_name = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        // SELECT * FROM $table_name WHERE email = :email AND firstname = :firstname

        $statement = self::prepare("SELECT *FROM $table_name WHERE $sql");
        foreach ($where as $key => $item){
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);

    }
}
