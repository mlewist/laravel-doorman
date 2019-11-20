<?php


namespace Redsnapper\LaravelDoorman\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Permission
{
    /**
     * The roles which this permission belongs to
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    public function findByName(string $name);

    public function getKey();

    public function getKeyName();
}
