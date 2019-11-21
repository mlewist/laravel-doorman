<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait IsPermission
{
    use HasRoles;

    /**
     * @param  string  $name
     * @return Permission|null
     * @throws Exception
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
