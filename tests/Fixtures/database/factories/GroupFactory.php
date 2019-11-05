<?php

use Faker\Generator as Faker;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Group;

$factory->define(Group::class, function (Faker $faker) {
    return [
      'name'    => $faker->jobTitle
    ];
});
