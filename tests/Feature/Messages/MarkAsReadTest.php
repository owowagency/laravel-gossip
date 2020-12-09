<?php

namespace OwowAgency\Gossip\Tests\Feature\Messages;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Tests\Support\Models\User;
use OwowAgency\Gossip\Tests\Support\Enumerations\Role;

class MarkAsReadTest extends TestCase
{
    /** @test */
    public function admin_can_mark_message_as_read(): void
    {
        [$user, $message] = $this->prepare();

        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        $response = $this->makeRequest($admin, $message);

        $this->assertResponse($response);

        $this->assertDatabase($admin, $message);
    }

    /** @test */
    public function user_can_mark_message_as_read(): void
    {
        [$user, $message] = $this->prepare();

        $response = $this->makeRequest($user, $message);

        $this->assertResponse($response);

        $this->assertDatabase($user, $message);
    }

    /** @test */
    public function user_cant_mark_message_as_read(): void
    {
        [$user, $message] = $this->prepare();

        $other = User::factory()->create();

        $response = $this->makeRequest($other, $message);

        $this->assertResponse($response, 403);
    }

    /**
     * Prepares for tests.
     *
     * @return array
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        $message = Message::factory()->create();

        // Add user as participant.
        $message->conversation->users()->attach($user);

        return [$user, $message];
    }

    /**
     * Makes a request.
     *
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User  $user
     * @param  \OwowAgency\Gossip\Models\Message  $message
     * @return \Illuminate\Testing\TestResponse
     */
    private function makeRequest(User $user, Message $message): TestResponse
    {
        return $this->actingAs($user)
            ->json('post', '/messages/' . $message->id . '/read');
    }

    /**
     * Asserts a response.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     * @param  int  $status
     * @return void
     */
    private function assertResponse(TestResponse $response, int $status = 200): void
    {
        $response->assertStatus($status);

        if ($status === 200) {
            $this->assertJsonStructureSnapshot($response);
        }
    }

    /**
     * Assert the database.
     *
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User  $user
     * @param  \OwowAgency\Gossip\Models\Message  $message
     * @return void
     */
    private function assertDatabase(User $user, Message $message): void
    {
        $this->assertDatabaseHas('message_user', [
            'user_id' => $user->id,
            'message_id' => $message->id,
        ]);
    }
}
