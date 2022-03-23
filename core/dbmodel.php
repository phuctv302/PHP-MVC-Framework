<?php
namespace core;

abstract class Dbmodel extends Model {
    abstract public function tableName(): string;

    abstract public function attributes(): array; // return all database columns name

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

    public static function prepare($sql){
        return Application::$app->db->pdo->prepare($sql);

    }
}
