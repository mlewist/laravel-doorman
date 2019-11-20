<?php

namespace Redsnapper\LaravelDoorman;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Models\Contracts\GroupInterface;
use Redsnapper\LaravelDoorman\Models\Contracts\PermissionContract;
use Redsnapper\LaravelDoorman\Models\Contracts\RoleInterface;

class PermissionsRegistrar
{
    /** @var Gate */
    protected $gate;

    /**
     * @var Collection
     */
    protected $permissions;

    protected $roleClass;

    protected $permissionClass;

    protected $groupClass;


    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
        $this->roleClass = config('doorman.role_class');
        $this->permissionClass = config('doorman.models.permission');
        $this->groupClass = config('doorman.group_class');
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
     * @return PermissionContract
     */
    public function getPermissionClass(): PermissionContract
    {
        return app($this->permissionClass);
    }

    /**
     * @return RoleInterface
     */
    public function getRoleClass(): RoleInterface
    {
        return app($this->roleClass);
    }

    /**
     * @return GroupInterface|null
     */
    public function getGroupClass(): ?GroupInterface
    {
        if(config('doorman.uses_groups')) {
            return app($this->groupClass);
        }

        return null;
    }

    /**
     * Ensure next time we ask for permissions they are returned from the database
     */
    public function forgetCachedPermissions()
    {
        $this->permissions = null;
    }

}
