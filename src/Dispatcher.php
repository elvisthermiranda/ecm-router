<?php

namespace Elvisthermiranda\Router;

class Dispatcher
{
    public function dispatch($routes, $requestMethod, $requestUri) {
        foreach ($routes as $route) {
            if ($route->matches($requestMethod, $requestUri)) {
                if (is_array($route->callback)) {
                    $controller = new $route->callback[0];
                    return $controller->$route->callback[1](...$route->getParameters());
                } else {
                    call_user_func_array($route->callback, $route->getParameters());
                }
                return;
            }
        }
        // 404 Not Found
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo "404 Not Found";
    }
}
