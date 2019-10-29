<?php

use Faker\Generator as Faker;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Permission;

$factory->define(Permission::class, function (Faker $faker) {
    return [
      'name'    => $this->faker->jobTitle,
    ];
});
