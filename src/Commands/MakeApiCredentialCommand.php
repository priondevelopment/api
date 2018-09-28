<?php

namespace Api\Commands;

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

class MakeApiCredentialCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionapi:api_credential';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Api Credential Model';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Api Credential model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/api_credential.stub';
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return config('api.models.api_credential', 'Credential');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Models\Api';
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
