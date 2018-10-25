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

    protected $brutePrefix = 'api';

    protected $brutePublicKey = "public_key";

    protected $bruteToken = "token";
    protected $bruteTokenRefresh = "token_refresh";
    protected $bruteOneTimeToken = "token_once";

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

}