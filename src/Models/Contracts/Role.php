<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

interface Role
{
    public function permissions();

    //public function givePermissionTo($permission);

    //public function removePermissionTo($permission);

    //public function getPermissionId($permission);

    public function hasPermission($permission);

    public function getKey();

    public function getKeyName();

    /**
     * Find a role by its name.
     *
     * @param  string  $name
     * @return Role
     */
    public static function findByName(string $name): self;
}
