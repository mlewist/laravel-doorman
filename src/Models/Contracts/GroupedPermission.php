<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;


interface GroupedPermission extends Permission
{
    public function allowsGroup(GroupInterface $group): bool;
}
