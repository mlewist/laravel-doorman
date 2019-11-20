<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Exceptions\CurrentGroupNotSetException;
use Redsnapper\LaravelDoorman\Models\Contracts\GroupedPermission;
use Redsnapper\LaravelDoorman\Models\Contracts\GroupInterface;
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
        /** @var GroupedPermission $permission */
        $permission = app(PermissionsRegistrar::class)->getPermissionClass()->findByName($permission);

        return (
          $this->hasPermission($permission) &&
          $permission->allowsGroup($this->currentGroup())
        );
    }

    /**
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(app(PermissionsRegistrar::class)->getGroupClass()
        )
          ->withPivot(
            ['is_current']
          );
    }

    /**
     * @return GroupInterface|null
     */
    public function currentGroup()
    {
        $group = $this->groups()->wherePivot('is_current', true)->first();

        if (empty($group)) {
            throw CurrentGroupNotSetException::create();
        }

        return $group;
    }

    /**
     * @param  GroupInterface  $group
     * @return $this
     */
    public function setCurrentGroup(GroupInterface $group)
    {
        if (!empty($this->groups()->wherePivot('is_current', true)->first())) {
            $this->groups()->syncWithoutDetaching([$this->currentGroup()->getKey(), ['is_current' => false]]);
        }

        $this->groups()->syncWithoutDetaching([$group->getKey() => ['is_current' => true]]);

        return $this;
    }
}
