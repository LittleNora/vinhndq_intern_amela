<?php

use Bramus\Router\Router;

$router = new Router();

$router->get('/', '\App\Controllers\HomeController@index');

$router->get('/contact', function () {
    echo 'Contact Page Contents';
});

$router->run();
