<?php

use Faker\Generator as Faker;

if(config('doorman.uses_groups')) {
    $factory->define(Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped\User::class, function (Faker $faker) {
        return [
            'name'    => $this->faker->unique()->name,
            'email'    => $this->faker->unique()->safeEmail,
            'password' => 'secret'
        ];
    });
} else {
    $factory->define(\Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User::class, function (Faker $faker) {
        return [
            'name'    => $this->faker->unique()->name,
            'email'    => $this->faker->unique()->safeEmail,
            'password' => 'secret'
        ];
    });
}
