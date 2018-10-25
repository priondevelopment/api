<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Api\Models\Scopes;
use Api\Models\Observers;

class ApiCredential extends Model
{

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
        $this->table = config('prionapi.tables.api_credential');
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

        ApiCredential::observe(Observers\ApiCredentialObserver::class);
    }


    /**
     * Return if Credential is Active?
     *
     */
    public function getIsActiveAttribute()
    {
        if (!$this->active)
            return false;

        if ($this->is_expired)
            return false;

        return true;

    }


    /**
     * Is the Credential Expired?
     *
     */
    public function getIsExpiredAttribute()
    {
        $expired = Carbon::parse($this->expires_at);
        $now = Carbon::now('UTC');

        if ($expired->lt($now))
            return true;

        return false;

    }

}