<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Role
{
    /**
     * A role may be given various permissions.
     *
     * @return BelongsToMany
     */
    public function permissions();

    /**
     * Get the primary key for the model.
     *
     * @return string
     */
    public function getKey();

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKeyName();

    /**
     * Find a role by its name.
     *
     * @param  string  $name
     * @return Role
     */
    public static function findByName(string $name): Role;
}
