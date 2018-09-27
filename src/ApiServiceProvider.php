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
        'Config' => 'command.prionapi.config',

        // Models
        'ModelApiCredential' => 'command.prionapi.model-api-credential',
        'ModelApiCredentialPermission' => 'command.prionapi.model-api-credential-permission',
        'ModelApiPermission' => 'command.prionapi.model-api-permission',
        'ModelApiToken' => 'command.prionapi.model-api-token',
        'ModelApiTokenUser' => 'command.prionapi.model-api-token-user',
    ];


    /**
     * The Routes the Run
     *
     * @var array
     */
    protected $routes = [
        'api',
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
        $app_path = app()->basePath('config/prionapi.php');
        $this->publishes([
            __DIR__ . '/config/prionapi.php' => $app_path,
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

        $this->mergeConfig();

        $this->registerRoutes();
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
                'prefix' => config('prionapi.base_path'),
            ], function ($router) use ($route) {
                require __DIR__.'/routes/'. $route .'.php';
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
        $this->app->configure('prionapi');
        $this->mergeConfigFrom(
            __DIR__.'/config/prionapi.php',
            'prionapi'
        );
    }


    /**
     * Register the Migration Command
     *
     */
    protected function registerMigrationCommand()
    {
        $this->app->singleton('command.prionapi.migration', function () {
            return new \Api\Commands\Migration;
        });
    }


    /**
     * Register the Seeder Command
     *
     */
    protected function registerSeederCommand()
    {
        $this->app->singleton('command.prionapi.seeder', function () {
            return new \Api\Commands\Seeder;
        });
    }


    /**
     * Register the Setup Command
     *
     */
    protected function registerSetupCommand()
    {
        $this->app->singleton('command.prionapi.setup', function () {
            return new \Api\Commands\Setup;
        });
    }


    /**
     * Register the Config Command
     *
     */
    protected function registerConfigCommand()
    {
        $command = $this->commands['Config'];
        $this->app->singleton($command, function () {
            return new \Api\Commands\ConfigCommand;
        });
    }


    /**
     * Register the ApiCredential Model
     *
     */
    protected function registerModelApiCredentialCommand()
    {
        $this->app->singleton('command.prionapi.model-api-credential', function () {
            return new \Api\Commands\MakeApiCredentialCommand;
        });
    }


    /**
     * Register the ApiCredentialPermission Model
     *
     */
    protected function registerModelApiCredentialPermissionCommand()
    {
        $this->app->singleton('command.prionapi.model-api-credential-permission', function () {
            return new \Api\Commands\MakeApiCredentialPermissionCommand;
        });
    }


    /**
     * Register the ApiPermission Model
     *
     */
    protected function registerModelApiPermissionCommand()
    {
        $this->app->singleton('command.prionapi.model-api-permission', function () {
            return new \Api\Commands\MakeApiPermissionCommand;
        });
    }


    /**
     * Register the ApiToken Model
     *
     */
    protected function registerModelApiTokenCommand()
    {
        $this->app->singleton('command.prionapi.model-api-token', function () {
            return new \Api\Commands\MakeApiToken;
        });
    }


    /**
     * Register the ApiTokenUser Model
     *
     */
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