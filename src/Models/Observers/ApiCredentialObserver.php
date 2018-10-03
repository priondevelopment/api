<?php

namespace Setting\Models\Observers;

/**
 * This file is part of Setting,
 * a setting management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use Setting\Models;

class ApiCredentialObserver
{

    /**
     * Cache Settings
     *
     * @var
     */
    protected $cache;
    protected $cacheTag = 'setting_cache';

    public function __construct()
    {
        $this->cache = app()->make('cache')
            ->tags($this->cacheTag);
    }

    /**
     * Observer when a Setting is Created
     *
     * @param Setting\Models\Settign $setting
     */
    public function created(Setting\Models\Setting $setting)
    {
        $this->log($setting->id);
    }


    /**
     * Observer when Setting is Updated
     *
     * @param Setting\Models\Setting $setting
     */
    public function updated(Setting\Models\Setting $setting)
    {
        $original = $setting->getOriginal();
        $this->log($setting->id, $origial->value);
        $this->clearCache($seetting->key);
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
