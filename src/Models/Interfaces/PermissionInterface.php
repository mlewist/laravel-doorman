<?php


namespace Redsnapper\LaravelDoorman\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

interface PermissionInterface
{
    public function findByName(string $name);

    public function roles(): BelongsToMany;

    public function getPermissions(): Collection;

    public function isActive(): bool;

    public function getKey();

    public function getKeyName();
}
