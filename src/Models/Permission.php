<?php

namespace Redsnapper\LaravelDoorman\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExistException;
use Redsnapper\LaravelDoorman\Models\Contracts\PermissionContract;
use Redsnapper\LaravelDoorman\Models\Traits\HasRoles;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

class Permission extends Model implements PermissionContract
{
    use HasRoles;

    /**
     * @param  string  $name
     * @return PermissionContract|null
     * @throws PermissionDoesNotExistException
     */
    public function findByName(string $name)
    {
        $permission = $this->getPermissions()->get($name);

        if (!$permission) {
            throw PermissionDoesNotExistException::create($name);
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