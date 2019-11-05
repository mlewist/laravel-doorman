<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Exception;
use Redsnapper\LaravelDoorman\Exceptions\CurrentGroupNotSetException;
use Redsnapper\LaravelDoorman\Models\Interfaces\GroupedPermissionInterface;
use Redsnapper\LaravelDoorman\Models\Interfaces\GroupInterface;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait IsGroupedUser
{
    use IsUser, HasGroups;

    /**
     * @param  string  $permission
     * @return bool
     * @throws Exception
     */
    public function hasPermissionTo(string $permission): bool
    {
        /** @var GroupedPermissionInterface $permission */
        $permission = app(PermissionsRegistrar::class)->getPermissionClass()->findByName($permission);

        return (
            $permission->isActive() &&
            $this->hasPermission($permission)  &&
            $permission->allowsGroup($this->currentGroup())
        );
    }

    /**
     * @return GroupInterface|null
     */
    public function currentGroup()
    {
        $group = $this->groups()->wherePivot('is_current', true)->first();

        if(empty($group)) {
            throw CurrentGroupNotSetException::create();
        }

        return $group;
    }

    /**
     * @param GroupInterface $group
     * @return $this
     */
    public function setCurrentGroup(GroupInterface $group): self
    {
        if(!empty($this->currentGroup())) {
            $this->groups()->syncWithoutDetaching([$this->currentGroup()->getKey(), ['is_current' => false]]);
        }

        $this->groups()->syncWithoutDetaching([$group->getKey(), ['is_current' => true]]);

        return $this;
    }
}
