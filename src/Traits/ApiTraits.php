<?php

namespace Api\Traits;

use Eloquent;
use App\Helpers\Tracking;
use App\Helpers\Error;
use App\Models;
use App\Libraries;
use App\Libraries\Brute\Brute;
use App\Exceptions;

use Api\Services;

trait ApiTraits
{

    /**
     * Cache Keys
     *
     * @var string
     */
    protected $cacheTag = 'api';
    protected $cacheCredential = 'credential';
    protected $cacheToken = 'token';
    protected $cacheTokenRefresh = 'token_refresh';
    protected $cacheTokenInitial = 'token_once';

    protected $cache;

    /**
     * Brute Cache Keys
     *
     * @var string
     */
    protected $brutePrefix = 'bruteApi';

    protected $brutePublicKey = "public_key";

    protected $bruteToken = "token";
    protected $bruteTokenRefresh = "token_refresh";
    protected $bruteOneTimeToken = "token_once";

    /**
     * Database Token Keys
     *
     * @var string
     */
    protected $TOKEN_INITIAL = "initial";
    protected $TOKEN = "token";
    protected $TOKEN_REFRESH = "refresh";


    /**
     *  Load Libraries/Helpers
     *
     */
    public function __construct ()
    {
        $this->error = app()->make('error');
        $this->brute = app()->make('brute');
        $this->input = app('request')->all();

        $this->cache = app()->make('cache')
            ->tags($this->cacheTag);
    }

    /**
     * Deactivate a Database Item
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->active = 0;
        $this->save();

        return $this;
    }


    /**
     * Determine the Cache Key
     *
     * The type is the same type as in the database (api_tokens.type)
     *
     * @param $type
     * @return string
     */
    public function cacheKey($type, $token='')
    {
        $type = strtolower($type);

        switch ($type) {
            case 'token':
                return $this->cacheToken . ":" . $token;
            case 'refresh':
                return $this->cacheTokenRefresh . ":" . $token;
            case 'initial':
                return $this->cacheTokenInitial . ":" . $token;
        }
    }

}