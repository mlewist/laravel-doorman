<?php

namespace Redsnapper\LaravelDoorman\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Guard;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\Models\Contracts\Role as RoleContract;
use Redsnapper\LaravelDoorman\Models\Traits\HasPermissions;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

class Role extends Model implements RoleContract
{
    use HasPermissions;

    /**
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany($this->getPermissionClass());
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Guard::getModelFor());
    }

    /**
     * Attach the given permissions
     *
     * @param  string|array|Permission|\Illuminate\Support\Collection  $permissions
     * @return Role
     */
    public function givePermissionTo(...$permissions):self
    {

        $this->permissions()->syncWithoutDetaching($this->getStoredPermissions($permissions));

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * @param  Permission|string  $permission
     * @return string
     */
    protected function getPermissionId($permission): string
    {
        if (is_string($permission)) {
            $permission = $this->getPermissionClass()
              ->findByName($permission)->getKey();
        }

        if ($permission instanceof Permission) {
            $permission = $permission->getKey();
        }

        return $permission;
    }

    /**
     * Forget the cached permissions.
     */
    private function forgetCachedPermissions()
    {
        app(PermissionsRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Remove the given permissions
     *
     * @param  Permission|Permission[]|string|string[]| $permission
     * @return Role
     */
    public function removePermissionTo(...$permissions):self
    {

        $this->permissions()->detach($this->getStoredPermissions($permissions));

        $this->load('permissions');

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Does this role have this permission
     *
     * @param  Permission|string  $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        return $this->permissions->contains($this->getPermissionClass()->getKeyName(),
          $this->getPermissionId($permission));
    }

    /**
     * Find a role by its name.
     *
     * @param  string  $name
     * @return RoleContract
     */
    public static function findByName(string $name): RoleContract
    {
        return static::where('name', $name)->first();
    }

    private function getStoredPermissions(array $permissions):array
    {
        return collect($permissions)->flatten()->map(function ($permission) {
            return $this->getPermissionId($permission);
        })->all();
    }

}