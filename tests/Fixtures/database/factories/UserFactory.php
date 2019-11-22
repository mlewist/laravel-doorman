<?php

use Faker\Generator as Faker;

$factory->define(\Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User::class, function (Faker $faker) {
    return [
      'name'    => $this->faker->unique()->name,
      'email'    => $this->faker->unique()->safeEmail,
      'password' => 'secret'
    ];
});
