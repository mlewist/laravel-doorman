<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Guard;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\Models\Contracts\Role as RoleContract;
use Redsnapper\LaravelDoorman\Models\Role;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissions
{
    use HasPermissionsTo;

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
    public function givePermissionTo(...$permissions): self
    {
        $this->permissions()->syncWithoutDetaching($this->getStoredPermissions($permissions));

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Sync the given permissions
     *
     * @param  string|array|Permission|\Illuminate\Support\Collection  $permissions
     * @return Role
     */
    public function syncPermissions(...$permissions): self
    {
        $this->permissions()->detach();

        return $this->givePermissionTo($permissions);
    }

    /**
     * Remove the given permissions
     *
     * @param  Permission|Permission[]|string|string[]| $permission
     * @return Role
     */
    public function removePermissionTo(...$permissions): self
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

    private function getStoredPermissions(array $permissions): array
    {
        return collect($permissions)->flatten()->map(function ($permission) {
            return $this->getPermissionId($permission);
        })->all();
    }

    /**
     * Forget the cached permissions.
     */
    private function forgetCachedPermissions()
    {
        app(PermissionsRegistrar::class)->forgetCachedPermissions();
    }

}
