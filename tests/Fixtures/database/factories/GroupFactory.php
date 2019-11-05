<?php

use Faker\Generator as Faker;

$factory->define(\Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped\Group::class, function (Faker $faker) {
    return [
      'name'    => $faker->jobTitle
    ];
});
