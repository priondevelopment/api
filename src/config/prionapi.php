<?php

return [

    'use_cache' => true,
    'cache_ttl' => 30, // in minutes

    'version' => "1.0",


    /**
    |--------------------------------------------------------------------------
    | The Base URL Path for the API
    |--------------------------------------------------------------------------
    |
    | For example: https://api.prionplatform.com/[base path here]
    |
    |   A sample call will look like this:
    |       https://api.prionplatform.com/api/version
    |
     */

    'base_path' => 'api',

    'hash_repeat' => 2,

    'auth_method' => 'double', // Single or Double

    'credentials' => [
        'expires_in' => 365*40, // Number of Years Credentials are Valid
        'never_expires' => false,
    ],
    'token' => [
        'expires_in' => 30, // Number of Days Token is Valid
        'never_expires' => false,
    ],
    'refresh' => [
        'expires_in' => 365, // Number of Days Refresh is Valid
        'never_expires' => true,
    ],

    'cache' => [
        'connect' => "api_connect",
        'credentials' => "api_credentials",
        'token' => "api_token",
    ],


    'middleware' => [
        'register' => true,
    ],


    /**
    |--------------------------------------------------------------------------
    | Default Seeded Values
    |--------------------------------------------------------------------------
    |
    | These values are seeded by default.
    |
    */
    'default' => [
        'credentials' => [
            'public_key' => '1234',
            'private_key' => '1234',
        ]
    ],


    /**
    |--------------------------------------------------------------------------
    | PrionApi Models
    |--------------------------------------------------------------------------
    |
    | These are the models used by Prion Settings
    |
    */
    'models' => [
        'api_credentials' => Api\Models\Api\Credential::class,
        'api_credential_permissions' => Api\Models\Api\CredentialPermission::class,
        'api_credential_permission_groups' => Api\Models\Api\CredentialPermissionGroup::class,
        'api_tokens' => Api\Models\Api\Token::class,

        'permissions' => Api\Models\Permission::class,
        'permission_groups' => Api\Models\PermissionGroup::class,
        'permission_group_permissions' => Api\Models\PermissionGroupPermission::class,
    ],



    /**
    |--------------------------------------------------------------------------
    | PrionApi Tables
    |--------------------------------------------------------------------------
    |
    | Tables are used to store user and account data for Prion Development
    | Api package.
    |
    */

    'tables' => [
        'api_credentials' => 'api_credentials',
        'api_credential_permissions' => 'api_credential_permissions',
        'api_credential_permission_groups' => 'api_credential_permission_groups',
        'api_tokens' => 'api_tokens',

        'permissions' => 'permissions',
        'permission_groups' => 'permission_groups',
        'permission_group_permissions' => 'permission_group_permissions',
    ],
];