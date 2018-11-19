<?php

namespace Api\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

use Api\Models;
use Api\Token;
use Api\Traits;

class PublicKeyService
{
    use Traits\ApiTraits;

    /**
     * Find Public Key, Check for Block
     *
     */
    public function get($public_key='')
    {
        $public_key = $public_key ?: $this->input['public_key'];
        $brute = $this->blocked($public_key);

        try {
        } catch (ModelNotFoundException $e) {
            $brute->attempt($public_key);
            $this->error->code('2001');
        }

        return $this->lookupCredentialsQuery($public_key);
    }

    /**
     * Lookup a Token in the Database. If cache is active, check the cache.
     *
     * @param $token
     * @param $type
     * @return mixed
     */
    protected function lookupToken($public_key)
    {
        if (!config('prionapi.use_cache')) {
            return $this->lookupCredentialsQuery($public_key);
        }

        $cacheKey = $this->cacheCredential . ":" . $public_key;
        $cacheTtl = config('prionapi.cache_ttl');
        $credential = $this->cache->remember($cacheKey, $cacheTtl, function ($public_key) {
            return $this->lookupCredentialsQuery($public_key);
        });

        $expires = Carbon::parse($credential->expires_at, 'UTC');
        $now = Carbon::now('UTC');
        if (!$credential->active) {
            $this->error->code('2011');
        }
        elseif ($expires->lte($now)) {
            $this->error->code('2011');
        }

        return $token;
    }

    /**
     * Query to Look Token
     *
     * @param $token
     * @param $type
     * @return mixed
     */
    protected function lookupCredentialsQuery($public_key)
    {
        return Models\Api\Credential::
            where('public_key', $public_key)
            ->firstOrFail();
    }


    /**
     * Check if the Public Key is Blocked. Throws a Json Exception
     *
     * @return mixed
     */
    protected function blocked($public_key)
    {
        $public_key = $public_key ?: $this->input['public_key'];
        $brute = $this->brute->get('block')
            ->reset($this->brutePrefix, $this->brutePublicKey);
        $brute->check($public_key);

        return $brute;
    }


}
