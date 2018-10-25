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

$router->get('version', function () use ($router) {
    return config('prionapi.version');
});

$router->group(['prefix' => 'token'], function () use ($router) {
    $router->get('{token}', 'TokenController@getStatus');
});