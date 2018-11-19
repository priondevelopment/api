<?php

namespace Api\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Filesystem\Filesystem;

class Setup extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionapi:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the Prion Development Api package.';

    /**
     * Commands to call with their description.
     *
     * @var array
     */
    protected $calls = [
        'prionapi:migration' => 'Creating migrations for PrionApi',
        'prionapi:model_api_credential' => 'Creating Api Credential model',
        'prionapi:model_api_credential_permission' => 'Creating Api Credential Permission',
        'prionapi:model_api_credential_permission_group' => 'Creating Api Credential Permission Group model',
        'prionapi:model_api_token' => 'Creating Api Token model',
        'prionapi:model_permission' => 'Creating Permissions model',
        'prionapi:model_permission_group' => 'Creating Permission Group model',
        'prionapi:model_permission_group_permission' => 'Creating Permission Group Permissions model',

        'prionapi:config' => "Creating PrionApi Config File",
    ];

    /**
     * Create a new notifications table command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @param  \Illuminate\Support\Composer $composer
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->calls as $command => $info) {
            $this->line(PHP_EOL . $info);
            $this->call($command);
        }
    }
}