<?php

namespace Api;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */

use Api\Models;

class Credentials
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    protected $credentials;

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


    /**
     * Pull the Credentials from a Public Key
     *
     * @param $public_key
     * @return mixed
     */
    private function getCredentials($public_key)
    {
        // Already Have Credentials
        if (is_object($public_key))
            return $public_key;

        // We have already looked up credentials
        if ($this->credentials)
            return $this->credentials;

        // Never Cache Credentials
        if (!config('prionapi.use_cache'))
            return $this->lookupCredentials($public_key);

        $key = 'public_key:' . $public_key;
        $time = config('prionapi.cache_ttl');
        return $this->cache->remember($key, $time, function () use ($public_key) {
            return $this->lookupCredentials($public_key);
        });
    }


    /**
     * Retrive the Api Credentials
     *
     * @param $public_key
     */
    private function lookupCredentials($public_key)
    {
        $this->credentials = Models\ApiCredential::
            select('id','active')
            ->where('public_key', $public_key)
            ->orderBy('id', 'DESC')
            ->first();

        return $this->credentials;
    }

}