<?php


namespace Redsnapper\LaravelDoorman\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

interface PermissionContract
{
    public function findByName(string $name);

    public function roles(): BelongsToMany;

    public function getPermissions(): Collection;

    public function getKey();

    public function getKeyName();
}
