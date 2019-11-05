<?php

namespace Redsnapper\LaravelDoorman\Exceptions;

use InvalidArgumentException;

class PermissionDoesNotExistException extends InvalidArgumentException
{
    public static function create(string $permissionName, string $guardName = '')
    {
        return new static("There is no permission named `{$permissionName}`.");
    }
}
