<?php

require_once __DIR__ . '/vendor/autoload.php';

use Elvisthermiranda\Router\Factory;

Factory::get('/hello', function() {
    echo "Hello, world!";
});

Factory::get('/albion', function() {
    echo "Hello, world!";
})
->setMiddleware([1,2,3])
->setName('ALBION');

Factory::get('/user/{id}/{uid}', function($id, $u) {
    echo "User {$id} - {$u}";
});

Factory::post('/submit', function() {
    echo "Form submitted!";
});

Factory::group([
    'prefix' => 'p1',
    'alias' => 'p1.',
    'middleware' => [1,2]
], function () {
    Factory::get('/a1', '')->setName('a1');
    Factory::get('/a2', '');

    Factory::group(['prefix' => 'sp1', 'alias' => 'sp1.'], function () {
        Factory::get('/sa1', '');
        Factory::get('/sa2/{id}', function ($id) {
            echo $id;
        })
        ->setMiddleware([3,4])
        ->setName('sa2');
    });
});

Factory::router()->dispatch();
