<?php
// autoload
include 'includes/autoloader.inc.php';
use controllers\Sitecontroller;
use core\Application;
use controllers\AuthController;

$app = new Application(__DIR__);

$app->router->get('/', [Sitecontroller::class, 'home']);
$app->router->get('/contact', [Sitecontroller::class, 'contact']);
$app->router->post('/contact', [Sitecontroller::class, 'handleContact']);

$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'login']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->run();