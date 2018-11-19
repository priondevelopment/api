<?php

namespace Api\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Api\Models\Scopes;

class CredentialPermissionGroup extends Model
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
        $this->table = config('prionapi.tables.api_credential_permission_groups');
    }

    /**
     * The "boot" method of the model
     *
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new Scopes\NotExpiredScope);
    }


}