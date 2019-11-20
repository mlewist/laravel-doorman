<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Redsnapper\LaravelDoorman\Models\Contracts\GroupInterface;

trait IsGroupedPermission
{
    use IsPermission, HasGroups;

    /**
     * @param GroupInterface $group
     * @return bool
     */
    public function allowsGroup(GroupInterface $group): bool
    {
        return $this->groups->isEmpty() || $this->groups->contains($group);
    }
}
