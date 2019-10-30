<?php

namespace Redsnapper\LaravelDoorman\Models\Interfaces;

interface UserInterface
{
    public function hasPermissionTo(string $permission);

    public function hasPermission(PermissionInterface $permission);

    public function assignRole(RoleInterface $role);

    public function hasRole($roles): bool;
}
