<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission as PermissionContract;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait PermissionIsFindable
{

    /**
     * @param  string  $name
     * @return Permission
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name): PermissionContract
    {
        $permission = static::getPermissions()->get($name);

        if (!$permission) {
            throw PermissionDoesNotExist::create($name);
        }

        return $permission;
    }

    /**
     * Get the current cached activities.
     *
     * @return Collection
     */
    protected static function getPermissions(): Collection
    {
        return app(PermissionsRegistrar::class)->getPermissions();
    }

}
