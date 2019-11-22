<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissions
{
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

    protected function getPermissionClass(): Permission
    {
        if (!isset($this->permissionClass)) {
            $this->permissionClass = app(PermissionsRegistrar::class)->getPermissionClass();
        }
        return $this->permissionClass;
    }


}
