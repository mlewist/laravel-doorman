<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class UserFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    public function definition()
    {
        return [
          'name'    => $this->faker->unique()->name,
          'email'    => $this->faker->unique()->safeEmail,
          'password' => 'secret'
        ];
    }

}
