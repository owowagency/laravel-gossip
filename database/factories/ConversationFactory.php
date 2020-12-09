<?php

namespace OwowAgency\Gossip\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ConversationFactory extends Factory
{
    /**
     * Get the name of the model that is generated by the factory.
     *
     * @return string
     */
    public function modelName()
    {
        return config('gossip.models.conversation');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
        ];
    }
}
