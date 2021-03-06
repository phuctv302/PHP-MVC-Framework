<?php
// autoload
include 'includes/autoloader.inc.php';

use core\Application;
use core\MyDotenv;

// config environment variables
$dotenv = new MyDotenv(__DIR__ . '/.env');
$dotenv->load();
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(__DIR__, $config);

$app->db->applyMigration();