<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Models\Contracts\Role;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasRoles
{
    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany($this->getRoleClass());
    }

    /**
     * @param  Role|int  ...$roles
     * @return self
     */
    public function assignRole(...$roles): self
    {
        $roles = collect($roles)->flatten()->map(function ($role) {
            return $this->getStoredRole($role);
        })->all();

        $this->roles()->syncWithoutDetaching($roles);

        $this->load('roles');

        return $this;
    }

    /**
     * Remove all current roles and set the given ones.
     *
     * @param  array|Role|string  ...$roles
     * @return $this
     */
    public function syncRoles(...$roles)
    {
        $this->roles()->detach();
        return $this->assignRole($roles);
    }

    /**
     *  Determine if the model has (one of) the given role(s).
     *
     * @param  Collection|Role  $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if ($roles instanceof Role) {
            return $this->roles->contains($roles->getKeyName(),
              $roles->getKey());
        }

        return $roles->intersect($this->roles)->isNotEmpty();
    }

    protected function getStoredRole($role): int
    {

        if (is_numeric($role)) {
            return $role;
        }

        if (is_string($role)) {
            $roleClass = $this->getRoleClass();

            $role = $roleClass->findByName($role);
        }

        return $role->getKey();
    }

    public function getRoleClass(): Role
    {
        if (!isset($this->roleClass)) {
            $this->roleClass = app(PermissionsRegistrar::class)->getRoleClass();
        }
        return $this->roleClass;
    }

}
