<?php

namespace OwowAgency\Gossip\Tests\Feature\Users\Conversations;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Tests\Support\Models\User;
use OwowAgency\Gossip\Tests\Support\Enumerations\Role;

class DestroyTest extends TestCase
{
    /** @test */
    public function a_user_can_leave_a_conversation(): void
    {
        [$user, $conversation] = $this->prepare();

        $response = $this->makeRequest($user, $conversation);

        $this->assertResponse($response);

        // Reload the conversations
        $user->load('conversations');

        $this->assertFalse($user->conversations->contains($conversation));
    }

    /** @test */
    public function a_user_cant_leave_a_conversation_they_are_not_in(): void
    {
        [$user, $conversation] = $this->prepare();

        $conversation = Conversation::factory()->create();

        $response = $this->makeRequest($user, $conversation);

        $this->assertResponse($response, 422);
    }

    /**
     * Prepares for tests.
     *
     * @return array
     */
    private function prepare(): array
    {
        $user = User::factory()
            ->hasConversations(1)
            ->create();

        return [$user, $user->conversations->first()];
    }

    /**
     * Makes a request.
     *
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User  $user
     * @param  \OwowAgency\Gossip\Models\Conversation  $conversation
     * @return \Illuminate\Testing\TestResponse
     */
    private function makeRequest(User $user, Conversation $conversation): TestResponse
    {
        return $this->actingAs($user)
            ->json('DELETE', "/conversations/$conversation->id/leave");
    }

    /**
     * Asserts a response.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     * @param  int  $status
     * @return void
     */
    private function assertResponse(TestResponse $response, int $status = 204): void
    {
        $response->assertStatus($status);
    }
}
