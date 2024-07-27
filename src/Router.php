<?php

namespace Elvisthermiranda\Router;

class Router implements RouterInterface
{
    private $routes = [];
    private $dispatcher;
    private $currentGroupPrefix = '';
    private $middlewareGroup = [];
    private $groupAlias = '';

    public function __construct(Dispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function add($method, $path, $callback) {
        $path = '/' . trim($this->currentGroupPrefix . trim($path, '/'), '/');
        $route = new Route($method, $path, $callback);

        if ($this->middlewareGroup) {
            $route->setMiddleware($this->middlewareGroup);
        }
        if ($this->groupAlias) {
            $route->setName($this->groupAlias);
        }

        $this->routes[] = $route;
        return $route;
    }

    public function get($path, $callback) {
        return $this->add('GET', $path, $callback);
    }

    public function post($path, $callback) {
        return $this->add('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        return $this->add('PUT', $path, $callback);
    }

    public function patch($path, $callback)
    {
        return $this->add('PATCH', $path, $callback);
    }

    public function delete($path, $callback)
    {
        return $this->add('DELETE', $path, $callback);
    }

    public function group(array $options, callable $callback) {
        $aliasBackup = $this->groupAlias;
        $prefixBackup = $this->currentGroupPrefix;
        $middlewareBackup = $this->middlewareGroup;
        if (isset($options['prefix'])) {
            $this->currentGroupPrefix .= trim($options['prefix'], '/') . '/';
        }
        if (isset($options['alias'])) {
            $this->groupAlias .= $options['alias'];
        }
        if (isset($options['middleware'])) {
            $this->middlewareGroup = array_unique(array_merge($this->middlewareGroup, $options['middleware']));
        }

        call_user_func($callback, $this);

        $this->currentGroupPrefix = $prefixBackup;
        $this->groupAlias = $aliasBackup;
        $this->middlewareGroup = $middlewareBackup;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function dispatch() {
        $this->dispatcher->dispatch($this->routes, $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    }
}
