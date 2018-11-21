<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
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
        $this->table = config('prionapi.tables.permission_groups');
    }


    /**
     * Pull All Permissions for a Group
     *
     */
    public function permissions()
    {
        return $this
            ->hasManyThrough(\Api\Models\Permission::class, \Api\Models\PermissionGroupPermission::class, 'permission_group_id', 'id');
    }


    /**
     * Pull All Permission Slugs
     *
     */
    public function getSlugsAttribute()
    {
        return $this->permissions->pluck('slug');
    }
}