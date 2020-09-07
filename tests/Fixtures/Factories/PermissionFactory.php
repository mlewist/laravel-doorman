<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Redsnapper\LaravelDoorman\Models\Permission;

class PermissionFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Permission::class;

    public function definition()
    {
        return [
          'name' => $this->faker->name
        ];
    }

}
