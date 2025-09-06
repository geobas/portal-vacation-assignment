<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../Helpers/view.php';
require_once __DIR__ . '/../Helpers/response.php';
require_once __DIR__ . '/../Helpers/csrf.php';

use App\Core\Container;
use App\Core\Request;
use App\Core\Router;
use App\Exceptions\HttpException;

try {
    $container = new Container();

    // Load bindings
    $bindings = require __DIR__ . '/../Core/Bindings.php';
    $bindings($container);

    // Initialize router
    $router = new Router(new Request(), $container);

    // Load routes
    $routes = require __DIR__ . '/../Routes/web.php';
    $routes($router);

    // Dispatch
    $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} catch (HttpException $e) {
    header('Location: /');
    exit;
}
