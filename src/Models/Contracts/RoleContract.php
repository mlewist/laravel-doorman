<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

interface RoleContract
{
    public function permissions();

    public function givePermissionTo($permission);

    public function removePermissionTo($permission);

    public function getPermissionId($permission);

    public function hasPermission($permission);

    public function getKey();

    public function getKeyName();
}
