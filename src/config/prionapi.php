<?php

return [

    'use_cache' => true,

    'version' => "1.0",

    /**
    |--------------------------------------------------------------------------
    | API Models
    |--------------------------------------------------------------------------
    |
    | These are the models used by Prion Settings
    |
    */
    'models' => [
        /**
         * Api Credential model
         */
        'api_credential' => 'App\Models\Api\Credential',

        /**
         * Api Credential model
         */
        'api_credential_permission' => 'App\Models\Api\CredentialPermission',

        /**
         * Api Permission model
         */
        'api_permission' => 'App\Models\Api\Permission',

        /**
         * Api Token model
         */
        'api_token' => 'App\Models\Api\Token',

        /**
         * Api Token User model
         */
        'api_token_user' => 'App\Models\Api\TokenUser',

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
        /**
         * Api Credentials Table
         *
         * API Credentials are used initially to validate a connection with the API.
         *
         */
        'api_credentials' => 'api_credentials',

        /**
         * API Credential Permissions
         *
         * API Credentials can be assigned permissions. These permissions limit the credential's
         * access to the API.
         */
        'api_credential_permissions' => 'api_credential_permissions',

        /**
         * API Permissions
         *
         * A list of permissions. These permissions are assignable to API Credentials
         */
        'api_permissions' => 'api_permissions',

        /**
         * API Tokens
         *
         * API Tokens are Valid Connections to the API.
         */
        'api_tokens' => 'api_tokens',

        /**
         * Users Associated to a Token
         *
         * A user can "log into" an API token. Once a user is logged into a token, the token has access
         * to everything the user has access. When a user logs out, the token is destroyed and the app
         * is required to create a new token.
         */
        'api_token_user' => 'api_token_user',

    ],
];