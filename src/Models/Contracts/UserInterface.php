<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

interface UserInterface
{
    public function hasPermissionTo(string $permission);

    public function hasPermission(PermissionContract $permission);

    public function assignRole(RoleInterface $role);

    public function hasRole($roles): bool;
}
