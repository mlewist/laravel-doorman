<?php


namespace Redsnapper\LaravelDoorman\Models\Traits;


trait IsGroup
{
    use HasPermissions, HasUsers;

    public function getPivotKeyName(): string
    {
        return 'group_id';
    }
}