<?php

namespace OwowAgency\Gossip\Factories;

use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userModel = config('gossip.user_model');

        return [
            'conversation_id' => Conversation::factory(),
            'user_id' => $userModel::factory(),
            'body' => $this->faker->text,
        ];
    }
}
