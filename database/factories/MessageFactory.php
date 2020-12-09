<?php

namespace OwowAgency\Gossip\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * Get the name of the model that is generated by the factory.
     *
     * @return string
     */
    public function modelName()
    {
        return config('gossip.models.message');
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'conversation_id' => config('gossip.models.conversation')::factory(),
            'user_id' => config('gossip.models.user')::factory(),
            'body' => $this->faker->text,
            'created_at' => $now = now(),
            'updated_at' => $now,
        ];
    }
}
