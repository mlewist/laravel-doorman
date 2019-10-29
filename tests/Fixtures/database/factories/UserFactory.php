<?php

use Faker\Generator as Faker;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

$factory->define(User::class, function (Faker $faker) {
    return [
      'username'    => $this->faker->unique()->name,
      'email'    => $this->faker->unique()->safeEmail,
      'password' => 'secret'
    ];
});
