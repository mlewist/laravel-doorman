<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\Models\Contracts\Role;
use Redsnapper\LaravelDoorman\Models\PermissionsRelation;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissionsViaRoles
{
    use HasRoles,HasPermissionsTo;

    public function permissions()
    {
        return new PermissionsRelation($this);
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

        return $this->permissions->contains($permission);
    }
}
