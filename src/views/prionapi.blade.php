<?php echo "<?php"; ?>

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
        'api_credentials' => 'Api\Models\Api\Credential',

        /**
         * Api Credential model
         */
        'api_credential_permissions' => 'Api\Models\Api\CredentialPermission',


        /**
         * Api Permission model
         */
        'api_groups' => 'Api\Models\Api\Group',

        /**
         * Api Permission model
         */
        'api_permissions' => 'Api\Models\Api\Permission',

        /**
         * Api Permission model
         */
        'api_permission_groups' => 'Api\Models\Api\PermissionGroup',

        /**
         * Api Token model
         */
        'api_tokens' => 'Api\Models\Api\Token',

        /**
         * Api Token User model
         */
        'api_token_user' => 'Api\Models\Api\TokenUser',

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
         * API Permission Groups
         *
         * Organize Permissions into Group
         */
        'api_permission_groups' => 'api_permission_groups',

        /**
         * Groups for Permissions
         *
         */
        'api_groups' => 'api_groups',

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