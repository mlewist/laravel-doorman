<?php

namespace Redsnapper\LaravelDoorman\Exceptions;

use InvalidArgumentException;

class CurrentGroupNotSetException extends InvalidArgumentException
{
    public static function create()
    {
        return new static("Current " . strtolower(config('doorman.group_name')) . " has not been set.");
    }
}
