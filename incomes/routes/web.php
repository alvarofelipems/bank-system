<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/api/incomes', 'IncomeController@index');
$router->post('/api/incomes', 'IncomeController@store');
$router->put('/api/incomes/{income}', 'IncomeController@update');

$router->get('/api/incomes/{income}/checks', 'CheckController@show');
$router->post('/api/incomes/{income}/checks', 'CheckController@store');

