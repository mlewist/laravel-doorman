<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExistException;
use Redsnapper\LaravelDoorman\Models\Interfaces\GroupInterface;
use Redsnapper\LaravelDoorman\Models\Interfaces\PermissionInterface;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait IsGroupedPermission
{
    use IsPermission, HasGroups;

    /**
     * @param GroupInterface $group
     * @return bool
     */
    public function allowsRegion(GroupInterface $group): bool
    {
        return $this->groups->isEmpty() || $this->groups->contains($group);
    }
}
