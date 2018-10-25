<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

use Api\Traits;

class ApiToken extends Model
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
        $this->table = config('prionapi.tables.api_token');
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
    }


    /**
     * Return the Credentials
     *
     * @return mixed
     */
    public function credentials()
    {
        return $this
            ->belongsTo('\Api\Models\ApiCredential','api_credential_id');
    }


    /**
     * Compare a Hash to Database Hash
     *
     * @param $hash
     * @return bool
     */
    public function compareHash($hash)
    {
        $serverHash = $this->hash;
        if ($hash != $serverHash) {
            app()->error()->code('2010');
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