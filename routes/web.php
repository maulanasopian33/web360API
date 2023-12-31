<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\Controller;

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
    // return $router->app->version();
    return "hello";
});

$router->post('/store','Controller@store');
$router->post('/upload','Controller@upload');
$router->get('/getfiles','Controller@getfiles');
$router->get('/get/{scene}','Controller@get');
$router->delete('/delete/{scene}','Controller@delete');
