<?php

namespace Elvisthermiranda\Router;

class Factory
{
    private static ?Router $router = null;

    public static function router(): Router
    {
        if (!self::$router) {
            $dispatcher = new Dispatcher();
            self::$router = new Router($dispatcher); 
        }
        return self::$router;
    }

    public static function get($path, $callback): Route
    {
        return self::router()->get($path, $callback);
    }

    public static function post($path, $callback): Route
    {
        return self::router()->post($path, $callback);
    }

    public static function put($path, $callback): Route
    {
        return self::router()->put($path, $callback);
    }

    public static function patch($path, $callback): Route
    {
        return self::router()->patch($path, $callback);
    }

    public static function delete($path, $callback): Route
    {
        return self::router()->delete($path, $callback);
    }

    public static function group($options, $callback)
    {
        return self::router()->group($options, $callback);
    }
}
