<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Models\Role;

class RoleFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    public function definition()
    {
        return [
          'name' => $this->faker->jobTitle
        ];
    }

}
