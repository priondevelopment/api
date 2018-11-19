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
use Illuminate\Console\Scheduling\Schedule;

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
        'ModelApiCredentialPermissionGroup' => 'command.prionapi.model-api-credential-permission-gropu',
        'ModelApiToken' => 'command.prionapi.model-api-token',

        'ModelPermission' => 'command.prionapi.model-permission',
        'ModelPermissionGroup' => 'command.prionapi.model-permission-group',
        'ModelPermissionGroupPermission' => 'command.prionapi.model-permission-group-permission',

        // General Maintenance
        'TokenDeleteExpired' => 'command.prionapi.token-delete-expired',
    ];


    /**
     * The Routes the Run
     *
     * @var array
     */
    protected $routes = [
        'api',
        'auth',
    ];

    /**
     * The middlewares to be registered.
     *
     * @var array
     */
    protected $middlewares = [
        'api_external' => \Api\Http\Middleware\External::class,
        'api_internal' => \Api\Http\Middleware\Internal::class,
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

        $this->registerMiddlewares();
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
     * Register the middlewares automatically.
     *
     * @return void
     */
    protected function registerMiddlewares()
    {
        if (!$this->app['config']->get('prionapi.middleware.register')) {
            return;
        }
        $router = $this->app['router'];
        if (method_exists($router, 'middleware')) {
            $registerMethod = 'middleware';
        } elseif (method_exists($router, 'aliasMiddleware')) {
            $registerMethod = 'aliasMiddleware';
        } else {
            return;
        }
        foreach ($this->middlewares as $key => $class) {
            $router->$registerMethod($key, $class);
        }
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
        if (!$this->app->runningInConsole()) {
            return false;
        }

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
     * Delete Expired Tokens and Credentials
     */
    public function registerTokenDeleteExpiredCommand()
    {
        $command = $this->commands['TokenDeleteExpired'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Token\DeleteExpired($app['files']);
        });
    }

    /**
     * Register the ApiCredential Model
     *
     */
    protected function registerModelApiCredentialCommand()
    {
        $this->app->singleton('command.prionapi.model-api-credential', function ($app) {
            return new \Api\Commands\Models\ApiCredential($app['files']);
        });
    }


    /**
     * Register the ApiCredentialPermission Model
     *
     */
    protected function registerModelApiCredentialPermissionCommand()
    {
        $this->app->singleton('command.prionapi.model-api-credential-permission', function ($app) {
            return new \Api\Commands\Models\ApiCredentialPermission($app['files']);
        });
    }


    /**
     * Register the ApiPermission Model
     *
     */
    protected function registerModelApiCredentialPermissionGroupCommand()
    {
        $command = $this->commands['ModelApiCredentialPermissionGroup'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Models\ApiCredentialPermissionGroup($app['files']);
        });
    }


    /**
     * Register the ApiPermission Model
     *
     */
    protected function registerModelApiTokenCommand()
    {
        $this->app->singleton('command.prionapi.model-api-token', function ($app) {
            return new \Api\Commands\Models\ApiToken($app['files']);
        });
    }


    /**
     * Register the ApiPermission Model
     *
     */
    protected function registerModelPermissionCommand()
    {
        $command = $this->commands['ModelPermission'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Models\Permission($app['files']);
        });
    }


    protected function registerModelPermissionGroupCommand()
    {
        $command = $this->commands['ModelPermissionGroup'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Models\PermissionGroup($app['files']);
        });
    }


    protected function registerModelPermissionGroupPermissionCommand()
    {
        $command = $this->commands['ModelPermissionGroupPermission'];
        $this->app->singleton($command, function ($app) {
            return new \Api\Commands\Models\PermissionGroupPermission($app['files']);
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