<?php

namespace Redsnapper\LaravelDoorman\Models\Interfaces;

interface GroupedUserInterface extends UserInterface
{
    public function currentGroup();

    public function setCurrentGroup(GroupInterface $group);

    public function groupKeyName(): string;

    public function groupPivotName(): string;

    public function groups(): bool;

    public function scopeForGroup();
}
