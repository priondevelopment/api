<?php

namespace Api;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */

use Carbon\Carbon;

use Api\Models;

class Credentials extends ApiAbstract implements Api\Contracts\ApiInterface
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    protected $credentials;

    protected $cacheTag;

    /**
     * Create a new confide instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->cacheTag = config('prionapi.cache.credentials');
        $this->cache();
    }


    /**
     * Pull the Credentials from a Public Key
     *
     */
    public function get($public_key)
    {
        $credentials = $this->pull($public_key);
        return $credentials;
    }


    /**
     * Does the Public Key Exist?
     *
     * @param $public_key
     */
    public function exist($public_key)
    {
        $credentials = $this->pull($public_key);

        if ($credentials->public_key)
            return true;

        return false;
    }


    /**
     * Is our public key available?
     *  1. Does it exist?
     *  2. Is it active?
     *  3. Is it expired?
     *
     * @param $public_key
     */
    public function active($public_key)
    {
        if (!$this->exists($public_key))
            return false;

        $credentials = $this->pull($public_key);

        if (!$credentials->active)
            return false;

        if ($this->expired($public_key))
            return false;

        return true;
    }

    /**
     * Has the Public Key Expired?
     *
     * @param $public_key
     */
    public function expired($public_key)
    {
        if (!$this->exists($public_key))
            return false;

        $credentials = $this->pull($public_key);
        $expired = Carbon::parse($credentials->expires_at);
        $now = Carbon::now('UTC');

        if ($expired->lt($now))
            return true;

        return false;
    }


    /**
     * Pull the Credentials from a Public Key
     *
     * @param $public_key
     * @return mixed
     */
    private function pull($public_key)
    {
        // Already Have Credentials
        if (is_object($public_key))
            return $public_key;

        // We have already looked up credentials
        if ($this->credentials)
            return $this->credentials;

        // Never Cache Credentials
        if (!config('prionapi.use_cache'))
            return $this->lookup($public_key);

        $key = 'public_key:' . $public_key;
        $time = config('prionapi.cache_ttl');
        return $this->cache->remember($key, $time, function () use ($public_key) {
            return $this->lookup($public_key);
        });
    }


    /**
     * Retrive the Api Credentials
     *
     * @param $public_key
     */
    private function lookup($public_key)
    {
        $this->credentials = Models\ApiCredential::
            select('id','active', 'internal', 'expires_at','account_id', 'user_id')
            ->where('public_key', $public_key)
            ->orderBy('id', 'DESC')
            ->first();

        return $this->credentials;
    }

}