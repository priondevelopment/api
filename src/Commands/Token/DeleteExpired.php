<?php

namespace Api\Commands\Token;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;

use Api\Models;
use Carbon\Carbon;

class DeleteExpired extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'prionapi:delete_token_expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired tokens.';

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
        $now = Carbon::now('UTC');
        $ids = Models\ApiToken::
            withoutGlobalScopes()
            ->where('expires_at', '<', $now->toDateTimeString())
            ->orWhere('active', '!=', 1)
            ->delete();
    }

}