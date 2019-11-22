<?php

namespace Redsnapper\LaravelDoorman;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\Models\Contracts\Role;

class PermissionsRegistrar
{
    /** @var Gate */
    protected $gate;

    /**
     * @var Collection
     */
    protected $permissions;

    /** @var string */
    protected $roleClass;

    /** @var string */
    protected $permissionClass;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
        $this->roleClass = config('doorman.models.role');
        $this->permissionClass = config('doorman.models.permission');
    }

    /**
     * Register activities for auth
     *
     */
    public function register()
    {
        //
        $this->gate->before(function (Authorizable $user, string $ability, $arguments) {

            // Must be passing a model with so we should ignore
            // Only want to check auth without arguments
            // Policies deal with authorization with arguments
            if (count($arguments) > 0) {
                return null;
            }

            return $user->hasPermissionTo($ability);
        });
    }

    /**
     * Returns all activities with the roles they belong to as well as all the
     * role activity instances
     *
     * @return Collection|static
     */
    public function getPermissions()
    {
        if (is_null($this->permissions)) {

            // We key by name so that when looking up activities we can find them
            // quicker
            $this->permissions = $this->getPermissionClass()
                ->with(['roles'])
                ->get()
                ->keyBy('name');
        }

        return $this->permissions;
    }

    /**
     * @return Permission
     */
    public function getPermissionClass(): Permission
    {
        return app($this->permissionClass);
    }

    /**
     * @return Role
     */
    public function getRoleClass(): Role
    {
        return app($this->roleClass);
    }


    /**
     * Ensure next time we ask for permissions they are returned from the database
     */
    public function forgetCachedPermissions()
    {
        $this->permissions = null;
    }

}
