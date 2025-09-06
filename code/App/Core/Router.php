<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    /**
     * @param array<string, array<string, array{0: class-string, 1: string}>> $routes
     */
    public function __construct(
        private Request $request,
        private Container $container,
        private array $routes = [],
    ) {
    }

    /**
     * Register a GET route.
     *
     * @param string $uri
     * @param array{0: class-string, 1: string} $action
     */
    public function get(string $uri, array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Register a POST route.
     *
     * @param string $uri
     * @param array{0: class-string, 1: string} $action
     */
    public function post(string $uri, array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Dispatch the request to the appropriate controller action.
     *
     * @param string $uri
     * @param string $method
     */
    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes[$method] ?? [] as $route => $action) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (!empty($uri) && preg_match($pattern, $uri, $matches)) {
                /** @var array<string, string> $params */
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$class, $methodName] = $action;
                $controller = $this->container->get($class);
                echo $controller->$methodName($this->request, ...array_values($params));

                return;
            }
        }

        http_response_code(404);
        echo view('404.php');
    }
}
