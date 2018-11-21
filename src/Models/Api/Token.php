<?php

namespace Api\Models\Api;

use Illuminate\Database\Eloquent\Model;

use Api\Traits;
use Api\Models\Scopes;

class Token extends Model
{

    use Traits\ApiTraits;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('prionapi.tables.api_tokens');
    }


    /**
     * The "boot" method of the model
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new Scopes\ActiveScope);
        static::addGlobalScope(new Scopes\NotExpiredScope);

        Token::observe(Observers\ApiTokenObserver::class);
    }


    /**
     * Return the Credentials
     *
     * @return mixed
     */
    public function credentials()
    {
        return $this
            ->belongsTo(\Api\Models\Api\Credential::class,'api_credential_id');
    }


    /**
     * Return the Token Credentials (Alias of credentials)
     *
     * @return mixed
     */
    public function credential()
    {
        return $this->credentials();
    }


    /**
     * Compare a Hash to Database Hash
     *
     * @param $hash
     * @return bool
     */
    public function compareHash($hash)
    {
        // No Need to Compare Hash, Hashing is Not Required
        if (config('prionapi.auth_method') == 'single') {
           return true;
        }

        $serverHash = $this->hash;
        if ($hash != $serverHash) {
            app()->make('error')->code('2010');
        }

        return true;
    }


    /**
     * Determine if the Token is Valid
     *
     */
    public function getValidAttribute()
    {
        if (!$this->active) {
            app()->make('error')->code(2016);
        }

        if (!$this->credentials OR !$this->credentials->count()) {
            app()->make('error')->code(2022);
        }

        return true;
    }


    /**
     * Pull the Hash
     *
     * @return string
     */
    protected function getHashAttribute()
    {
        $repeat = config('prionapi.hash_repeat');
        $hash = md5($this->credentials->public_key) ."_"
            . md5($this->credentials->token) ."_"
            . md5($this->credentials->private_key);

        for ($i=0; $i < $repeat; $i++) {
            $hash = md5($hash);
        }

        return $hash;
    }


    /**
     * Pull the Logged In User
     *
     * @return mixed
     */
    public function user()
    {
        return $this
            ->belongsToOne('\Users\Models\User', 'user_id');
    }
}