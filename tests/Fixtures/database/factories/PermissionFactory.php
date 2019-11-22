<?php

use Faker\Generator as Faker;
use Redsnapper\LaravelDoorman\Models\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
      'name' => $faker->jobTitle,
    ];
});
