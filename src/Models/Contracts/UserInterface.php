<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

interface UserInterface
{
    public function hasPermissionTo(string $permission);

    public function hasPermission(Permission $permission);

}
