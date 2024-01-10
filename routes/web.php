<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\Controller;
use App\Http\Controllers\lisensiController;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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

    // $payload = [
    //     'domain' => "http://localhost:5173/Virtual360/",
    //     'register_by' => 'maulana@antmediahost.com',
    // ];

    // // Kunci rahasia untuk menandatangani token
    // $secretKey = 'pucukidea';

    // // Membuat token JWT
    // $token = JWT::encode($payload, $secretKey,'HS256');

    // return $token;

});
$router->post('/login','Controller@login');
$router->get('/updatelisensi','lisensiController@updatelisensi');
$router->get('/verifylisensi','lisensiController@ceklisensi');
$router->post('/store','Controller@store');
$router->post('/upload','Controller@upload');
$router->get('/getfiles','Controller@getfiles');
$router->get('/get/{scene}','Controller@get');
$router->delete('/delete/{scene}','Controller@delete');
