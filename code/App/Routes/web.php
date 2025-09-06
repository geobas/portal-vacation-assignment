<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\VacationController;
use App\Core\Router;

/**
 * Web Routes
 *
 * @param Router $router
 * @return void
 */
return function (Router $router): void {
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
    $router->post('/users/vacations/{id}/approve', [UserController::class, 'approve']);
    $router->post('/users/vacations/{id}/reject', [UserController::class, 'reject']);

    // Vacations
    $router->get('/vacations', [VacationController::class, 'index']);
    $router->get('/vacations/create', [VacationController::class, 'create']);
    $router->post('/vacations', [VacationController::class, 'store']);
    $router->post('/vacations/{id}/delete', [VacationController::class, 'destroy']);
};
