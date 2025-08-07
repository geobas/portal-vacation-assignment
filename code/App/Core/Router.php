<?php

namespace App\Core;

class Router
{
    public function __construct(
        private Request $request,
        private array $routes = [],
    ){
        $this->request = $request;
    }

    public function get(string $uri, array $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(string $uri, string $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$class, $methodName] = $action;
                $controller = new $class();
                echo $controller->$methodName($this->request, ...array_values($params));
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
