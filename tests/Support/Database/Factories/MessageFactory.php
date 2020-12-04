<?php

namespace OwowAgency\Gossip\Tests\Support\Database\Factories;

use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Models\Conversation;
use Illuminate\Database\Eloquent\Factories\Factory;
use OwowAgency\Gossip\Tests\Support\Models\User;

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
        return [
            'conversation_id' => Conversation::factory(),
            'user_id' => User::factory(),
            'body' => $this->faker->text,
        ];
    }
}
