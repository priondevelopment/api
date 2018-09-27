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
     * Create a new confide instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * Check if Public Key Exists and Is Valid
     *
     * @param null $public_key
     * @return \Illuminate\Database\Eloquent\Model|null|static
     * @throws Exceptions\ReturnException
     */
    public function publicKey($public_key='')
    {

    }

}