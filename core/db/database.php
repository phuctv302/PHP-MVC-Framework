<?php

namespace core\db;

use core\Application;
use PDO;

/*
 * connect to database (MySql PDO)
 * apply migrations
 * */
class Database {
    public $pdo;

    /**
     * connect to database
     * @throws \Exception if connect fail
     * @param array $config from $_ENV, store some configurations for connecting to db
     * */
    public function __construct($config){
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn, $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /*
     * Migration is for reference
     * Don't be bother with this at first =)))
     * */
    public function applyMigration(){
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigration();

        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrations as $migration){
            if ($migration === '.' || $migration === '..'){
                continue;
            }

            require_once Application::$ROOT_DIR . '/migrations/' . $migration;
            $class_name = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $class_name();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Apllied migration $migration");
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationTable(){
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ) ENGINE=INNODB;");
    }

    public function getAppliedMigration(){
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    public function saveMigrations($migrations){
        $str = implode(",", array_map(function ($m){
            return "('$m')";
        }, $migrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
            $str
        ");
        $statement->execute();
    }

    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }

    protected function log($message){
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
