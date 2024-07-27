<?php

namespace Elvisthermiranda\Router;

class Route
{
    public $method;
    public $path;
    public $callback;
    private $parameters = [];
    public $name;
    public $middlewares = [];

    public function __construct($method, $path, $callback) {
        $this->method = $method;
        $this->path = $path;
        $this->callback = $callback;
    }

    public function matches($requestMethod, $requestUri) {
        if ($this->method !== $requestMethod) {
            return false;
        }

        $pathPattern = preg_replace('/\{[^\}]+\}/', '([^/]+)', $this->path);
        $pathPattern = str_replace('/', '\/', $pathPattern);

        if (preg_match('/^' . $pathPattern . '$/', $requestUri, $matches)) {
            array_shift($matches);
            $this->parameters = $matches;
            return true;
        }

        return false;
    }

    public function getParameters() {
        return $this->parameters;
    }

    public function setName(string $name)
    {
        $this->name .= $name;
    }

    public function setMiddleware($middlewares)
    {
        if (is_array($middlewares)) {
            $this->middlewares = array_unique(array_merge($this->middlewares, $middlewares));
        } else {
            $this->middlewares[] = $middlewares;
        }
        
        return $this;
    }
}
