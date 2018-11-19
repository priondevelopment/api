<?php

namespace Api\Commands\Models;

/**
 * This file is part of Setting,
 * a setting management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Console\GeneratorCommand;

class PermissionGroupPermission extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionapi:model_permission_group_permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Api Permission Group Permission Command Model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Api Group Command Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/permission_group_permission.stub';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return config('api.models.permission_group_permission', 'PermissionGroupPermission');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Models';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }
}
