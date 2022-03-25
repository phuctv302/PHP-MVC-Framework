<?php
// autoload
include 'includes/autoloader.inc.php';

use core\Application;
use core\Dotenv;

// config environment variables
$dotenv = new Dotenv(__DIR__ . '/.env');
$dotenv->load();
$config = [
    'userClass' => \models\User::class,
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(__DIR__, $config);

$app->db->applyMigration();