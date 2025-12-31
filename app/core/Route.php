<?php

class Route
{
    private static $routes = [];

    public static function get($uri, $callback)
    {
        self::$routes['GET'][$uri] = $callback;
    }

    public static function post($uri, $callback)
    {
        self::$routes['POST'][$uri] = $callback;
    }

    public static function dispatch($uri, $method)
    {
        foreach (self::$routes[$method] as $route => $callback) {
            // Convert route to regex: /users/{id} -> /users/(\w+)
            $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $route);
            $route = '#^' . $route . '$#';

            if (preg_match($route, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $params = $matches;

                if (is_callable($callback)) {
                    call_user_func_array($callback, $params);
                    return;
                }

                if (is_string($callback)) {
                    list($controller, $method) = explode('@', $callback);
                    
                    // The autoloader will handle this
                    if (class_exists($controller)) {
                        $controllerInstance = new $controller();
                        if (method_exists($controllerInstance, $method)) {
                            call_user_func_array([$controllerInstance, $method], $params);
                            return;
                        }
                    }
                }
            }
        }

        // Handle 404
        http_response_code(404);
        echo '404 Not Found';
    }

    public static function getUri()
    {
        $basePath = rtrim(parse_url(URL_ROOT, PHP_URL_PATH) ?? '', '/');
        $requestUri = $_SERVER['REQUEST_URI'];

        // Remove query string
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        $uri = rawurldecode($requestUri);

        // If the request URI starts with the base path, remove it
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        return trim($uri, '/');
    }

    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
