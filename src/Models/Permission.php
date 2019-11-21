<?php

namespace Redsnapper\LaravelDoorman\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission as PermissionContract;
use Redsnapper\LaravelDoorman\Models\Traits\HasRoles;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

class Permission extends Model implements PermissionContract
{
    use HasRoles;

    /**
     * @param  string  $name
     * @return Permission|null
     * @throws PermissionDoesNotExist
     */
    public function findByName(string $name)
    {
        $permission = $this->getPermissions()->get($name);

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
    public function getPermissions(): Collection
    {
        return app(PermissionsRegistrar::class)->getPermissions();
    }

}