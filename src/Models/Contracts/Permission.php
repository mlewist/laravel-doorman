<?php


namespace Redsnapper\LaravelDoorman\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;

interface Permission
{
    /**
     * The roles which this permission belongs to
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * Find a permission by its name.
     *
     * @param string $name
     *
     * @throws PermissionDoesNotExist
     *
     * @return Permission
     */
    public static function findByName(string $name): Permission;

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKeyName();
}
