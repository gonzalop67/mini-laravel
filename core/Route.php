<?php

namespace Core;

class Route
{
    private static array $routes = [];

    public static function get(string $uri, callable|array $callback, array $middlewares = [])
    {
        $uri = trim($uri, '/');
        self::$routes['GET'][$uri] = $callback;
        self::$routes['GET'][$uri]['middlewares'] = $middlewares;
    }

    public static function post(string $uri, callable|array $callback, array $middlewares = [])
    {
        $uri = trim($uri, '/');
        self::$routes['POST'][$uri] = $callback;
        self::$routes['POST'][$uri]['middlewares'] = $middlewares;
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];

        // Limpiar la URI
        $uri = parse_url($uri, PHP_URL_PATH);

        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $basePath = str_replace('/public', '', $basePath);

        $uri = str_replace($basePath, '', $uri);
        $uri = parse_url($uri, PHP_URL_PATH);

        $uri = trim($uri, '/');

        $method = $_SERVER['REQUEST_METHOD'];

        // 1. Ejecutar los middlewares primero
        foreach (self::$routes[$method][$uri]['middlewares'] as $middleware) {
            $middleware();
            // show($middleware);
        }
        // die();

        foreach (self::$routes[$method] as $route => $callback) {

            if (strpos($route, ':') !== false) {
                $route = preg_replace('#:[a-zA-Z]+#', '([a-zA-Z0-9]+)', $route);
            }

            if (preg_match("#^$route$#", $uri, $matches)) {

                $params = array_slice($matches, 1);

                if (is_callable($callback)) {
                    $response = $callback(...$params);
                }

                if (is_array($callback)) {
                    $controller = new $callback[0];
                    $response = $controller->{$callback[1]}(...$params);
                }

                if (is_array($response) || is_object($response)) {
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else
                    echo $response;

                return;
            }
        }

        echo "404 Not Found";
    }
}
