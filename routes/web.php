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
$router->get('/api', function () use ($router) {
    return response()->json(['message' => 'DrQueue Api', 'version' => '1.0']);
});
$router->group(['prefix' => 'api', 'namespace' => 'App\Http\Controllers'], function () use ($router) {
    $router->get('test',function () use ($router){
       return response()->json(['message'=> 'Ok']);
    });
});




