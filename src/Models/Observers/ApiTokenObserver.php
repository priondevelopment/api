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
use Api\Traits;

class ApiTokenObserver
{
    use Traits\ApiTraits;

    protected $cache;

    /**
     * Observer when a Token is Created or Updated
     *
     * @param Models\ApiToken $token
     */
    public function saved(Models\Api\Token $token)
    {
        $this->updateCache($token);
    }

    /**
     * Clear the Cache when Values are Created or Updated
     *
     * @param $key
     */
    private function updateCache($token)
    {
        if (config('prionapi.use_cache')) {
            $cacheKey = $this->cacheKey($token->type, $token->token);
            $this->cache->forget($cacheKey);

            if ($token->active) {
                $cacheTtl = config('prionapi.cache_ttl');
                $this->cache->put($token, $cacheKey, $cacheTtl);
            }
        }
    }
}