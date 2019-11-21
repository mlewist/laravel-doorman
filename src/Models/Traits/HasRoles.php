<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

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

    //TODO Scope roles

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

        // Reload the roles for this model
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
     * @param  string|int|array|Role|\Illuminate\Support\Collection  $roles
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('name', $roles);
        }

        if ($roles instanceof Role) {
            return $this->roles->contains($roles->getKeyName(),
              $roles->getKey());
        }

        if (is_int($roles)) {
            return $this->roles->contains('id', $roles);
        }

        if (is_array($roles)) {

            return collect($roles)->contains(function ($role) {
                return $this->hasRole($role);
            });
        }

        return $roles->intersect($this->roles)->isNotEmpty();
    }

    /**
     * Revoke the given role from the model.
     *
     * @param  string|Role  $role
     */
    public function removeRole($role): self
    {
        $this->roles()->detach($this->getStoredRole($role));
        $this->load('roles');
        $this->forgetCachedPermissions();
        return $this;
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

    protected function getRoleClass(): Role
    {
        if (!isset($this->roleClass)) {
            $this->roleClass = app(PermissionsRegistrar::class)->getRoleClass();
        }
        return $this->roleClass;
    }

    /**
     * Forget the cached permissions.
     */
    private function forgetCachedPermissions()
    {
        app(PermissionsRegistrar::class)->forgetCachedPermissions();
    }

}
