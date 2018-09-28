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
        'prionapi:migration' => 'Creating migration',
        'prionapi:api_credential' => 'Creating Api Credential model',
        'prionapi:api_credential_permission' => 'Creating Api Credential Permission Log model',
        'prionapi:api_permission' => 'Creating Api Permisson model',
        'prionapi:api_group' => 'Creating Api Permisson model',
        'prionapi:api_group_permission' => 'Creating Api Permisson model',
        'prionapi:api_token' => 'Creating api token model',
        'prionapi:api_token_user' => 'Creating api token use model',
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