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
        return $this->belongsToMany(app(PermissionsRegistrar::class)->getPermissionClass());
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Guard::getModelFor());
    }

    /**
     * @param  string|array|Permission|\Illuminate\Support\Collection  $permissions
     */
    public function givePermissionTo(...$permissions)
    {

        $permissions = collect($permissions)->flatten()->map(function ($permission) {
            return $this->getPermissionId($permission);
        })->all();

        $this->permissions()->syncWithoutDetaching($permissions);

        $this->forgetCachedPermissions();
    }

    /**
     * @param  Permission|string  $permission
     * @return string
     */
    protected function getPermissionId($permission): string
    {
        if (is_string($permission)) {
            $permission = (app(PermissionsRegistrar::class)->getPermissionClass())
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
     * @param $permission
     * @throws Exception
     */
    public function removePermissionTo($permission)
    {
        $permissionId = $this->getPermissionId($permission);

        $this->permissions()->detach([$permissionId]);

        $this->forgetCachedPermissions();
    }

    /**
     * Does this role have this permission
     *
     * @param  Permission|string  $permission
     * @return bool
     * @throws Exception
     */
    public function hasPermission($permission): bool
    {
        return $this->permissions->contains(app(PermissionsRegistrar::class)->getPermissionClass()->getKeyName(),
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
        $role = static::where('name', $name)->first();

        return $role;
    }

}