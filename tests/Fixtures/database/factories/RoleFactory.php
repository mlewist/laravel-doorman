<?php

use Faker\Generator as Faker;
use Redsnapper\LaravelDoorman\Models\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [
      'name' => $this->faker->jobTitle,
    ];
});
