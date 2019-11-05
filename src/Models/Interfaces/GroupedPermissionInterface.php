<?php

namespace Redsnapper\LaravelDoorman\Models\Interfaces;


interface GroupedPermissionInterface extends PermissionInterface
{
    public function allowsGroup(GroupInterface $group): bool;
}
