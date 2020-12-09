<?php

namespace OwowAgency\Gossip\Tests\Unit\Support\Collection;

use Illuminate\Support\Collection;
use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Tests\Support\Models\User;
use OwowAgency\Gossip\Support\Collection\MessageCollection;

class MessageCollectionTest extends TestCase
{
    /** @test */
    public function messages_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();
        $messages = Message::factory()->count(5)->create();

        $collection = new MessageCollection($messages);

        $collection->markAsRead($user);

        foreach ($this->getDatabaseAttributes($collection, $user) as $attributes) {
            $this->assertDatabaseHas('message_user', $attributes);
        }
    }

    /** @test */
    public function messages_can_be_marked_as_unread(): void
    {
        $user = User::factory()
            ->hasReadMessages(5)
            ->create();

        $collection = new MessageCollection($user->readMessages->load('users'));

        // We'll make sure that one message is read, so we're 100% sure the test
        // works.
        $this->assertTrue($collection->first()->read($user));

        $collection->markAsUnread($user);

        foreach ($this->getDatabaseAttributes($collection, $user) as $attributes) {
            $this->assertDatabaseMissing('message_user', $attributes);
        }
    }

    /**
     * Transform the message collection to usable format for the database.
     *
     * @param  \OwowAgency\Gossip\Support\Collection\MessageCollection  $collection
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    protected function getDatabaseAttributes(MessageCollection $collection, $user): Collection
    {
        return $collection->map(fn($message) => [
            'message_id' => $message->id,
            'user_id' => $user->id,
        ]);
    }
}
