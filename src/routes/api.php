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

$router->group([
    'prefix' => 'token',
    'middleware' => 'api_internal:credential_read,credential_write',
], function () use ($router) {
    $router->get('{token}', 'TokenController@getStatus');
});

$router->group([
    'middleware' => 'api_internal'
], function () use ($router) {

    $router->group([
        'prefix' => 'credentials',
        'middleware' => 'api_internal:credential_read,credential_write',
    ], function () use ($router) {
        $router->get('/', 'CredentialController@all');
    });

    $router->group([
        'prefix' => 'credential',
        'middleware' => 'api_internal:credential_read,credential_write',
    ], function () use ($router) {
        $router->get('{id}', 'CredentialController@get');
    });

    $router->group([
        'prefix' => 'credential',
        'middleware' => 'api_internal:credential_write',
    ], function () use ($router) {
        $router->post('/', 'CredentialController@');
        $router->post('{credential_id}', 'CredentialController@');
        $router->post('{credential_id}/tokens', 'CredentialController@');
        $router->post('{credential_id}/tokens/{id}/expire', 'CredentialController@');
        $router->post('{credential_id}/tokens/{id}/expires', 'CredentialController@');
        $router->post('{credential_id}/tokens/{id}/delete', 'CredentialController@');
    });

    // View All Permission Groups or Single Permission Group
    $router->group([
        'prefix' => 'permission',
        'middleware' => 'api_internal:permission_group_read,permission_group_write',
    ], function () use ($router) {
        $router->get('groups', 'PermissionGroupController@all');
        $router->get('group/{slug}', 'PermissionGroupController@get');
    });

    // Create or Update a Permission Group
    $router->group([
        'prefix' => 'permission/group',
        'middleware' => 'api_internal:permission_group_write',
    ], function () use ($router) {
        $router->post('/', 'PermissionGroupController@insert');
        $router->post('{slug}', 'PermissionGroupController@update');
    });

    // Associate Permissions Groups with Permissions
    $router->group([
        'prefix' => 'permission/{permission_slug}/group/{group_id}',
        'middleware' => 'api_internal:permission_group_manage',
    ], function () use ($router) {
        $router->get('/', 'PermissionGroup@get');
        $router->post('/', 'PermissionGroup@update');
        $router->post('expires', 'PermissionGroup@expiresAt');
        $router->get('expire', 'PermissionGroup@expire');
        $router->get('delete', 'PermissionGroup@delete');
    });

    // Associate Credentials with Permissions
    $router->group([
        'prefix' => 'credential/{credential_id}/permission/{permission}',
        'middleware' => 'api_internal:permission_credential_manage',
    ], function () use ($router) {
        $router->post('/', 'CredentialPermission@update');
        $router->post('expires', 'CredentialPermission@expiresAt');
        $router->get('expire', 'CredentialPermission@expire');
        $router->get('delete', 'CredentialPermission@delete');
    });

    // Associate Credentials with Permissions Groups
    $router->group([
        'prefix' => 'credential/{credential_id}/permission-group/{permission_group}',
        'middleware' => 'api_internal:permission_group_permission_manage',
    ], function () use ($router) {
        $router->post('/', 'CredentialPermissionGroup@update');
        $router->post('expires', 'CredentialPermissionGroup@expiresAt');
        $router->get('expire', 'CredentialPermissionGroup@expire');
        $router->get('delete', 'CredentialPermissionGroup@delete');
    });

    // Read all Permissions
    $router->group([
        'prefix' => 'permissions',
        'middleware' => 'api_internal:permission_write,permission_read',
    ], function () use ($router) {
        $router->get('/', 'PermissionController@all');
    });

    // Create or Update a Permission
    $router->group([
        'prefix' => 'permission',
        'middleware' => 'api_internal:permission_write',
    ], function () use ($router) {
        $router->post('/', 'PermissionController@insert');
        $router->get('{slug}', 'PermissionController@get');
        $router->post('{slug}', 'PermissionController@update');
    });
});