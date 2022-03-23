<?php
// autoload
include 'includes/autoloader.inc.php';
use core\Application;
use core\Dotenv;
use controllers\Sitecontroller;
use controllers\AuthController;

// config environment variables
$dotenv = new Dotenv(__DIR__.'/.env');
$dotenv->load();
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

$app = new Application(__DIR__, $config);

$app->router->get('/', [Sitecontroller::class, 'home']);
$app->router->get('/contact', [Sitecontroller::class, 'contact']);
$app->router->post('/contact', [Sitecontroller::class, 'handleContact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->run();