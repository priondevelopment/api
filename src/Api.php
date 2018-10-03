<?php

namespace Api;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */

class Api
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;


    /**
     * Load Organized Classes
     *
     * @var array
     */
    protected $load = [
        'Connect',
        'Credentials',
        'Token',
    ];

    /**
     * Create a new confide instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->load();
    }


    /**
     * Check if Public Key Exists and Is Valid
     *
     * @param null $public_key
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws Exceptions\ReturnException
     */
    public function load()
    {
        foreach ($this->load as $load) {
            $this->{$load} = new Api\{$load};
        }
    }

}