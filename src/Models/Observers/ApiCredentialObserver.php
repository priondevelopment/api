<?php

namespace Api\Models\Observers;

/**
 * This file is part of Setting,
 * a setting management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use Api\Models;

class ApiCredentialObserver
{

    /**
     * Cache Settings
     *
     * @var
     */
    protected $cache;
    protected $cacheTag = 'api_cache';

    public function __construct()
    {
        $this->cache = app()->make('cache')
            ->tags($this->cacheTag);

        $this->brute = app()->make('brute');
    }

    /**
     * Observer when a Setting is Created
     *
     * @param Setting\Models\Settign $setting
     */
    public function created(Models\Api\Credential $credential)
    {
        $this->log($credential->id);
    }


    /**
     * Observer when Setting is Updated
     *
     * @param Setting\Models\Setting $setting
     */
    public function updated(Models\Api\Credential $credential)
    {
        $original = $credential->getOriginal();
        $this->log($credential->id, $origial->value);
        $this->clearCache($credential->key);
    }


    /**
     * When Retriving a Model, Check if a Value Exists
     *
     * @param Models\ApiCredential $credential
     */
    public function retrieved(Models\Api\Credential $credential)
    {
    }


    /**
     * Log the Setting Change
     *
     * @param $setting_id
     * @param string $previous
     */
    private function log($setting_id, $previous='')
    {
        // Check if Logging is Enabled
        if (!config('setting.enable_logging'))
            return false;

        $auth = app()->make('auth');
        $user_id = $auth->check() ? $auth->user()->id : 0;
        Setting\Models\Setting::insert([
            'user_id' => $user_id,
            'setting_id' => $setting_id,
            'previous' => $previous,
        ]);
    }


    /**
     * Clear the Cache when Values are Updated
     *
     * @param $key
     */
    private function clearCache($key)
    {
        $this->cache->forget($key);
        $this->cache->forget("exists:" . $key);
    }

}
