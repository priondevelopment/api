<?php

namespace Api;

/**
 * This file is part of Setting,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

interface ApiAbstract
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Cache Settings
     *
     * @var
     */
    protected $cache;
    protected $cacheTag = 'api_cache';

    protected $type;

    /**
     * Create a new confide instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->cache();
    }


    /**
     * Setup the Cache
     *
     * @return mixed
     */
    protected function cache()
    {
        $this->cache = app()->make('cache')
            ->tags($this->cacheTag);
    }

}