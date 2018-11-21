<?php

namespace Api\Services;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

use Api\Models;
use Api\Traits;

class TokenService
{
    use Traits\ApiTraits;

    /**
     * Find Token, Check for Blocks
     *
     */
    public function get($token='', $type='token')
    {
        $token = $token ?: $this->input['token'];

        if (!$token) {
            $this->error->code('2015');
        }

        $type = $this->tokenType($type);
        $brute = $this->blocked($token);

        try {
            $token = $this->lookupToken($token, $type);
        } catch (ModelNotFoundException $e) {
            $brute->attempt($token);
            $this->error->code('2011');
        }

        return $token;
    }


    /**
     * Lookup a Token in the Database. If cache is active, check the cache.
     *
     * @param $token
     * @param $type
     * @return mixed
     */
    protected function lookupToken($token, $type)
    {
        if (!config('prionapi.use_cache')) {
            return $this->lookupTokenQuery($token, $type);
        }

        $cacheKey = $this->cacheKey($type, $token);
        $cacheTtl = config('prionapi.cache_ttl');
        $token = $this->cache->remember($cacheKey, $cacheTtl,
            function () use ($token, $type) {
                return $this->lookupTokenQuery($token, $type);
            });

        $expires = Carbon::parse($token->expires_at, 'UTC');
        $now = Carbon::now('UTC');
        if (!$token->active) {
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
    protected function lookupTokenQuery($token, $type)
    {
        return Models\Api\Token::
            where('token', $token)
            ->where('type', $type)
            ->firstOrFail();
    }


    /**
     * Create a One Time Use Token
     *
     * @param $credentials
     * @return Models\ApiToken
     */
    public function createInitial($credentials)
    {
        $length = mt_rand(99,124);
        $now = Carbon::now('UTC');

        $token              = $this->build($credentials, $length);
        $token->type        = $this->TOKEN_INITIAL;
        $token->expires_at  = $now->addMinutes('30');
        $token->save();

        return $token;
    }


    /**
     * Create a Token from Credentials
     *
     * @param $credentials
     * @return mixed|string
     */
    public function createToken($credentials)
    {
        $length = mt_rand(41,49);
        $now = Carbon::now('UTC');

        $token              = $this->build($credentials, $length);
        $token->type        = $this->TOKEN;
        $token->expires_at  = $now->addDays( config('prionapi.token.expires_in') );

        if ( config('prionapi.token.never_expires')) {
            $token->expires_at = $now->addYears(1000);
        }

        $token->save();

        return $token->token;
    }


    /**
     * Build a Refresh Token
     *
     * @param $credentials
     * @return mixed
     */
    public function createRefresh($credentials)
    {
        $length = mt_rand(81,87);
        $now = Carbon::now('UTC');

        $token              = $this->build($credentials, $length);
        $token->type        = $this->TOKEN_REFRESH;
        $token->expires_at  = $now->addDays( config('prionapi.refresh.expires_in') );

        if ( config('prionapi.refresh.never_expires')) {
            $token->expires_at = $now->addYears(1000);
        }

        $token->save();

        return $token->token;
    }


    /**
     * Model to Build New Token
     *
     * @param $credentials
     * @param $length
     * @return Models\ApiToken
     */
    protected function build($credentials, $length)
    {
        $token                      = new Models\Api\Token;
        $token->token               = time() . '_' . $token->id . '_' . str_random($length);
        $token->ip                  = $this->ip();
        $token->device_id           = e(app('request')->input('device_id'));
        $token->api_credential_id   = $credentials->id;
        $token->active              = 1;

        return $token;
    }


    /**
     * Pull IP Address
     *
     * @return int
     */
    public function ip()
    {
        $ip_keys = [
            "HTTP_CLIENT_IP",
            "HTTP_X_FORWARDED_FOR",
            "HTTP_X_FORWARDED",
            "HTTP_FORWARDED_FOR",
            "HTTP_FORWARDED",
            "REMOTE_ADDR",
        ];

        foreach ($ip_keys as $key) {

            if (!isset($_SERVER[$key])) {
                continue;
            } elseif ($_SERVER["$key"]) {
                return $_SERVER["$key"];
            }

        }

        // Default to 0
        return 0;
    }


    /**
     * Determine the Token Type
     *
     * @param $type
     */
    protected function tokenType($type)
    {
        $type = strtolower($type);

        switch ($type) {
            case 'refresh':
                return $this->TOKEN_REFRESH;

            case 'once':
            case 'initial':
                return $this->TOKEN_INITIAL;

            default:
                return $this->TOKEN;
        }
    }


    /**
     * Check if the Token is Blocked
     *
     * @param string $token
     * @return mixed
     */
    protected function blocked($token)
    {
        $brute = $this->brute->get('block')
            ->reset($this->brutePrefix, $this->bruteToken);
        $brute->check($token);

        return $brute;
    }
}