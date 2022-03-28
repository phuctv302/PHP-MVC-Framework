<?php
// autoload
include 'includes/autoloader.inc.php';

use core\Application;
use core\Dotenv;
use controllers\SiteController;
use controllers\AuthController;

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

// Event
//$app->on(Application::EVENT_BEFORE_REQUEST, function (){
//    echo 'Before request';
//});
//$app->on(Application::EVENT_BEFORE_REQUEST, function (){
//    echo 'Before request 2';
//});

/*
 * SiteController::class return "SiteController"
 * */
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'contact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
$app->router->get('/logout', [AuthController::class, 'logout']);
$app->router->get('/profile', [AuthController::class, 'profile']);

// FIX BUG
$app->router->post('/profile', [AuthController::class, 'updateUser']);

$app->run();