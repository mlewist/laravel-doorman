<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\Models\Contracts\Role;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissionsViaRoles
{
    use HasRoles,HasPermissionsTo;

    /**
     * Has permission
     *
     * @param  Permission  $permission
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        $permissionClass = $this->getPermissionClass();

        return $permission->roles
          ->pluck($permissionClass->getKeyName())
          ->intersect(
            $this->roles->pluck($permissionClass->getKeyName())
          )->isNotEmpty();
    }
}
