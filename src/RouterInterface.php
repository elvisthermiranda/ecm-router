<?php

namespace Elvisthermiranda\Router;

interface RouterInterface {
    public function get($path, $callback);
    public function post($path, $callback);
    public function put($path, $callback);
    public function patch($path, $callback);
    public function group(array $options, callable $callback);
    public function dispatch();
}
