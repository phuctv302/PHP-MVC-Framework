<?php
// autoload
include dirname(__DIR__) . '/includes/autoloader.inc.php';

use core\Application;
use core\MyDotenv;
use controllers\SiteController;
use controllers\AuthController;

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


/*
 * SiteController::class return "SiteController"
 * */
$app->router->get('/', [SiteController::class, 'home']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->get('/logout', [AuthController::class, 'logout']);

$app->router->get('/forgot', [AuthController::class, 'forgot']);
$app->router->post('/forgot', [AuthController::class, 'forgot']);
$app->router->get('/reset', [AuthController::class, 'reset']);
$app->router->post('/reset', [AuthController::class, 'reset']);

$app->router->get('/profile', [AuthController::class, 'profile']);
$app->router->post('/profile', [AuthController::class, 'updateUser']);
$app->router->post('/profile-image', [AuthController::class, 'updatePhoto']);

$app->run();