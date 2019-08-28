<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/hi', function () use ($router) {
    return "Hello, Abhinav";
});

// Order List API
$router->get('orders', 'OrderController@list');
// Order Create
$router->post('orders', 'OrderController@store');
// Order Update
$router->patch('orders/{orderID}', 'OrderController@take');
