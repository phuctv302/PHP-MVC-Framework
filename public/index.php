<?php
// autoload
include dirname(__DIR__) . '/includes/autoloader.inc.php';

use controllers\AuthController;
use controllers\SiteController;
use controllers\UserController;
use core\Application;
use core\MyDotenv;
use middlewares\AuthMiddleware;
use middlewares\CsrfMiddleware;

// config environment variables
$dotenv = new MyDotenv(dirname(__DIR__) . '/.env');
$dotenv->load();
$config = [
    'db' => [
        'dsn' => $_ENV['DB_DSN'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    ]
];

/**
 * @catch error connecting to database
 * */
try {
    $app = new Application(dirname(__DIR__), $config);
} catch (Exception $e) {
    Application::$app->response->setStatusCode($e->getCode());
    echo Application::$app->view->render('_error', [
        'exception' => $e
    ]);
    exit;
}


/*
 * SiteController::class return "SiteController"
 * */

// Register path, callback [Controller, action] and middlewares
$app->router->get('/login', [SiteController::class, 'loginForm']);
$app->router->post('/login', [AuthController::class, 'login']);

$app->router->get('/register', [SiteController::class, 'registerForm']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->router->post('/logout', [AuthController::class, 'logout'], [new CsrfMiddleware()]);

$app->router->get('/forgot', [SiteController::class, 'forgotPasswordForm']);
$app->router->post('/forgot', [AuthController::class, 'forgotPassword']);
$app->router->get('/reset', [SiteController::class, 'resetPasswordForm']);
$app->router->post('/reset', [AuthController::class, 'resetPassword']);

$app->router->get('/profile', [SiteController::class, 'profile'], [new AuthMiddleware()]);
$app->router->post('/profile', [UserController::class, 'updateUser'], [new AuthMiddleware(), new CsrfMiddleware()]);
$app->router->post('/profile-image', [UserController::class, 'updatePhoto'], [new AuthMiddleware(), new CsrfMiddleware()]);

// Redirect to login if user has not logged in yet, otherwise redirect account page
$app->router->get('/', [SiteController::class, 'home'], [new AuthMiddleware()]);

// execute the callback and middlewares
$app->run();