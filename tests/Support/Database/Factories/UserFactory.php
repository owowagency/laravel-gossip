<?php

namespace OwowAgency\Gossip\Tests\Support\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use OwowAgency\Gossip\Tests\Support\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [];
    }
}
