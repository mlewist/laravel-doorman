<?php

namespace Redsnapper\LaravelDoorman\Models\Contracts;

interface Role
{
    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
    public static function findByName(string $name): self;
}
