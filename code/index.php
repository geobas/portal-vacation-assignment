<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Router;
use App\Core\Request;
use App\Controllers\AuthController;
use App\Controllers\UserController;

$router = new Router(new Request());

// Authentication
$router->get('/', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// Managers
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users', [UserController::class, 'store']);
$router->get('/users/{id}/edit', [UserController::class, 'edit']);
$router->post('/users/{id}', [UserController::class, 'update']);
$router->post('/users/{id}/delete', [UserController::class, 'destroy']);

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
