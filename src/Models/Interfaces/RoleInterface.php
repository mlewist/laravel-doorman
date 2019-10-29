<?php

namespace Redsnapper\LaravelDoorman\Models\Interfaces;

interface RoleInterface
{
    function permissions();

    function givePermissionTo($permission);

    function removePermissionTo($permission);

    function getPermissionId($permission);

    function hasPermission($permission);

    function getKey();
}
