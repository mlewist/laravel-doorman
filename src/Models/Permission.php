<?php

namespace Redsnapper\LaravelDoorman\Models;

use Illuminate\Database\Eloquent\Model;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission as PermissionContract;
use Redsnapper\LaravelDoorman\Models\Traits\HasRoles;
use Redsnapper\LaravelDoorman\Models\Traits\PermissionIsFindable;

class Permission extends Model implements PermissionContract
{
    use HasRoles, PermissionIsFindable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

}