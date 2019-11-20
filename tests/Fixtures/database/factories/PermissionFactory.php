<?php

use Faker\Generator as Faker;
use Redsnapper\LaravelDoorman\Models\Permission;

if(config('doorman.uses_groups')) {
    $factory->define(Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped\Permission::class, function (Faker $faker) {
        return [
            'name' => 'Some weird shit',
        ];
    });
} else {
    $factory->define(Permission::class, function (Faker $faker) {
        return [
            'name' => $faker->jobTitle,
        ];
    });
}
