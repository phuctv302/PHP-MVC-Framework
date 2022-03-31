<?php
// autoload
include 'includes/autoloader.inc.php';

use core\Application;
use core\MyDotenv;

// config environment variables
$dotenv = new MyDotenv(dirname(__DIR__) . '/.env');
$dotenv->load();
$config = [
    'userClass' => \models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(dirname(__DIR__), $config);

$app->db->applyMigration();