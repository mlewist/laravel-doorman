<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Exception;
use Redsnapper\LaravelDoorman\Models\Contracts\GroupInterface;
use Redsnapper\LaravelDoorman\Models\Contracts\PermissionContract;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait IsUser
{
    use HasRoles;

    /**
     * @param  string  $permission
     * @return bool
     * @throws Exception
     */
    public function hasPermissionTo(string $permission): bool
    {
        /** @var PermissionContract $permission */
        $permission = app(PermissionsRegistrar::class)->getPermissionClass()->findByName($permission);

        return ($this->hasPermission($permission));
    }

    public function hasPermission(PermissionContract $permission): bool
    {
        return $permission->roles
          ->pluck(app(PermissionsRegistrar::class)->getPermissionClass()->getKeyName())
          ->intersect(
            $this->roles->pluck(app(PermissionsRegistrar::class)->getRoleClass()->getKeyName())
          )->isNotEmpty();
    }
}
