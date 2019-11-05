<?php

namespace Redsnapper\LaravelDoorman\Models\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface GroupedUserInterface extends UserInterface
{
    public function currentGroup();

    public function setCurrentGroup(GroupInterface $group);

    public function groupKeyName(): string;

    public function groupPivotKeyName(): string;

    public function groups();

    public function scopeForGroup(Builder $query, Collection $groups);
}
