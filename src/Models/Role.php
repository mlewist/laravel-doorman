<?php

namespace Redsnapper\LaravelDoorman\Models;

use Illuminate\Database\Eloquent\Model;
use Redsnapper\LaravelDoorman\Models\Contracts\Role as RoleContract;
use Redsnapper\LaravelDoorman\Models\Traits\HasPermissions;

class Role extends Model implements RoleContract
{
    use HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];


}