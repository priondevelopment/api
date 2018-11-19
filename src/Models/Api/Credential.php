<?php

namespace Api\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Api\Models\Scopes;
use Api\Models\Observers;

class Credential extends Model
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
        $this->table = config('prionapi.tables.api_credentials');
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

        Credential::observe(Observers\ApiCredentialObserver::class);
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


    /**
     * Pull Available Permissions to Credential
     *
     * @return mixed
     */
    public function permissions()
    {
        return $this
            ->hasManyThrough(\Api\Models\Permission::class, \Api\Models\Api\CredentialPermission::class);
    }


    /**
     * Pull all Permission Groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    protected function permissionGroups()
    {
        return $this
            ->hasManyThrough(\Api\Models\PermissionGroup::class, \Api\Models\Api\CredentialPermissionGroup::class);
    }


    /**
     * Pull All Slugs from Permissions and Permission Groups
     *
     * @return mixed
     */
    public function getSlugAttributes()
    {
        $slugs = $this->permissionSlugs;
        return $slugs;
    }


    /**
     * Pull All Write Permission Slugs
     *
     */
    public function getPermissionSlugsAttributes()
    {
        $permissions = $this
            ->permissions()
            ->select('slug')
            ->pluck('slug');
        $groupPermissions = $this
            ->permissionGroups();

        foreach ($groupPermissions as $groupPermission) {
            $perms = $groupPermission->slugs;
            $permissions = $permissions->merge($perms);
        }

        return $permissions;
    }

}