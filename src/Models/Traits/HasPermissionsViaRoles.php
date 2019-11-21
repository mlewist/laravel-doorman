<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\Models\Contracts\Role;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissionsViaRoles
{
    use HasRoles;

    /**
     * @var Permission
     */
    private $permissionClass;

    /**
     * @param  string|Permission  $permission
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo($permission): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission);
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist;
        }

        return $this->hasPermission($permission);
    }

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

    protected function getPermissionClass(): Permission
    {
        if (!isset($this->permissionClass)) {
            $this->permissionClass = app(PermissionsRegistrar::class)->getPermissionClass();
        }
        return $this->permissionClass;
    }



}
