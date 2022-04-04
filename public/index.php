<?php
// autoload
include dirname(__DIR__) . '/includes/autoloader.inc.php';

use controllers\UserController;
use core\Application;
use core\middlewares\AuthMiddleware;
use core\middlewares\CsrfMiddleware;
use core\MyDotenv;
use controllers\SiteController;
use controllers\AuthController;

// config environment variables
$dotenv = new MyDotenv(dirname(__DIR__) . '/.env');
$dotenv->load();
$config = [
    'user_class' => \models\User::class,
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

$app->router->get('/login', [SiteController::class, 'loginForm']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/register', [SiteController::class, 'registerForm']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->post('/logout', [AuthController::class, 'logout'], [new CsrfMiddleware()]);

$app->router->get('/forgot', [SiteController::class, 'forgotPasswordForm']);
$app->router->post('/forgot', [AuthController::class, 'forgotPassword']);
$app->router->get('/reset', [SiteController::class, 'resetPasswordForm']);
$app->router->post('/reset', [AuthController::class, 'resetPassword']);

$app->router->get('/profile', [SiteController::class, 'profile'], [new AuthMiddleware(['profile'])]);
$app->router->post('/profile', [UserController::class, 'updateUser'], [new CsrfMiddleware()]);
$app->router->post('/profile-image', [UserController::class, 'updatePhoto'], [new CsrfMiddleware()]);

$app->router->get('/', [SiteController::class, 'home']);

$app->run();