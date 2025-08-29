<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/App/Helpers/view.php';
require_once __DIR__ . '/App/Helpers/response.php';
require_once __DIR__ . '/App/Helpers/csrf.php';

use App\Core\Router;
use App\Core\Request;
use App\Core\Container;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\VacationController;
use App\Exceptions\HttpException;
use App\Contracts\VacationRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Repositories\VacationRepository;
use App\Repositories\UserRepository;

try {
    $container = new Container();

    // Load bindings
    $bindings = require __DIR__ . '/App/Core/Bindings.php';
    $bindings($container);

    // Initialize router
    $router = new Router(new Request(), $container);

    // Load routes
    $routes = require __DIR__ . '/App/Routes/web.php';
    $routes($router);    

    // Dispatch
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (HttpException $e) {
    header('Location: /');
    exit;    
}
