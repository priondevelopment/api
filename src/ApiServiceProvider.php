<?php

namespace Api;

/**
 * This file is part of Prion Development API,
 * a api credential and token management.
 *
 * @license MIT
 * @package Api
 */

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Migration' => 'command.prionapi.migration',
        'Setup' => 'command.prionapi.setup',
        'Seeder' => 'command.prionapi.seeder',

        // Models
        'ModelApiCredential' => 'command.prionapi.model-api-credential',
        'ModelApiCredentialPermission' => 'command.prionapi.model-api-credential-permission',
        'ModelApiPermission' => 'command.prionapi.model-api-permission',
        'ModelApiToken' => 'command.prionapi.model-api-token',
        'ModelApiTokenUser' => 'command.prionapi.model-api-token-user',
    ];

    protected $routes = [
        'auth',
        'validate',
    ];

    /**
     * The middlewares to be registered.
     *
     * @var array
     */
    protected $middlewares = [
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Register published configuration.
        $this->publishes([
            __DIR__ . '/config/prionapi.php' => config_path('prionapi.php'),
        ], 'prionapi');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApi();

        $this->registerCommands();

        $this->registerRoutes();

        $this->mergeConfig();
    }


    /**
     * Register PrionUsers Package in Laravel/Lumen
     *
     */
    protected function registerApi()
    {
        $this->app->bind('api', function ($app) {
            return new Api($app);
        });

        $this->app->alias('api', 'Api\Api');

    }


    /**
     * Register the Available Commands
     *
     */
    protected function registerCommands ()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }


    /**
     * Register all Route Files
     *
     */
    protected function registerRoutes()
    {
        foreach ($this->routes as $route) {
            $this->app->router->group([
                'namespace' => 'Api\Http\Controllers',
                'prefix' => 'api',
            ], function ($router) {
                require __DIR__.'/../routes/'. $route .'.php';
            });
        }
    }


    /**
     * Merge Configuration Settings at run time. If the API has not run
     * the configuration setup command, the default setings are merged in
     *
     */
    protected function mergeConfig ()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/prionapi.php',
            'prionapi'
        );
    }


    protected function registerMigrationCommand()
    {
        $this->app->singleton('command.prionapi.migration', function () {
            return new \Api\Commands\Migration;
        });
    }


    protected function registerSeederCommand()
    {
        $this->app->singleton('command.prionapi.seeder', function () {
            return new \Api\Commands\Seeder;
        });
    }


    protected function registerSetupCommand()
    {
        $this->app->singleton('command.prionapi.setup', function () {
            return new \Api\Commands\Setup;
        });
    }


    protected function registerModelApiCredentialCommand()
    {
        $this->app->singleton('command.prionapi.model-api-credential', function () {
            return new \Api\Commands\MakeApiCredentialCommand;
        });
    }


    protected function registerModelApiCredentialPermissionCommand()
    {
        $this->app->singleton('command.prionapi.model-api-credential-permission', function () {
            return new \Api\Commands\MakeApiCredentialPermissionCommand;
        });
    }


    protected function registerModelApiPermissionCommand()
    {
        $this->app->singleton('command.prionapi.model-api-permission', function () {
            return new \Api\Commands\MakeApiPermissionCommand;
        });
    }


    protected function registerModelApiTokenCommand()
    {
        $this->app->singleton('command.prionapi.model-api-token', function () {
            return new \Api\Commands\MakeApiToken;
        });
    }


    protected function registerModelApiTokenUserCommand()
    {
        $this->app->singleton('command.prionapi.model-api-token-user', function () {
            return new \Api\Commands\MakeApiTokenUser;
        });
    }


    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return array_values($this->commands);
    }
}