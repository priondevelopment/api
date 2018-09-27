<?php

namespace Api;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */

class Credentials
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
     * Pull the Credentials from a Public Key
     *
     */
    public function get($public_key)
    {

    }


    /**
     * Does the Public Key Exist?
     *
     * @param $public_key
     */
    public function exist($public_key)
    {

    }


    /**
     * Does the Public Key Exist and is it active?
     *
     * @param $public_key
     */
    public function active($public_key)
    {

    }

    /**
     * Has the Public Key Expired?
     *
     * @param $public_key
     */
    public function expired($public_key)
    {

    }

}