<?php

namespace OwowAgency\Gossip\Tests\Unit\Models;

use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Tests\Support\Models\User;

class MessageTest extends TestCase
{
    /** @test */
    public function message_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create();

        $message->markAsRead($user);

        $this->assertDatabaseHas('message_user', [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);
    }

    /** @test */
    public function message_can_be_marked_as_unread(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create();

        // Mark message as read.
        $message->users()->attach($user);

        $message->markAsUnread($user);

        $this->assertDatabaseMissing('message_user', [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);
    }

    /** @test */
    public function message_can_be_read(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create();

        $message->markAsRead($user);

        $message->load('users');

        $this->assertTrue($message->read($user));
        $this->assertFalse($message->unread($user));
    }

    /** @test */
    public function message_can_be_unread(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create();

        // Mark message as read.
        $message->users()->attach($user);

        $message->markAsUnread($user);

        $message->load('users');

        $this->assertFalse($message->read($user));
        $this->assertTrue($message->unread($user));
    }
}
