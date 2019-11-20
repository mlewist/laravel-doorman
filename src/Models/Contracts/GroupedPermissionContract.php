<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;


interface GroupedPermissionContract extends PermissionContract
{
    public function allowsGroup(GroupInterface $group): bool;
}
