<?php

$router->post('auth', 'AuthController@postAuth');
$router->post('once', "AuthController@postOnce");
$router->post('single', "AuthController@postSingle");
$router->post('refresh', "AuthController@postRefresh");
$router->post('hash', "AuthController@postHash");