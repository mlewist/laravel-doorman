<?php

use Faker\Generator as Faker;

$factory->define(\Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Role::class, function (Faker $faker) {
    return [
      'name' => $this->faker->jobTitle,
    ];
});
