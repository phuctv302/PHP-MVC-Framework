<?php

class m0001_initial {
    public function up(){
        $db = \core\Application::$app->db;
        $SQL = "CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                username VARCHAR(255) NOT NULL,
                job_title VARCHAR(255) NOT NULL,
                address VARCHAR(255),
                birthday DATE NOT NULL,
                phone INT,
                photo VARCHAR(255) DEFAULT 'default.jpg',
                reset_token VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP             
        ) ENGINE=INNODB;";
        $db->pdo->exec($SQL);
    }

    public function down(){
        $db = \core\Application::$app->db;
        $SQL = "DROP TABLE users;";
        $db->pdo->exec($SQL);
    }
}
