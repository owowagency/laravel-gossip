<?php

namespace OwowAgency\Gossip\Tests\Feature\Models\Conversations;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\Support\Models\User;

class ShowTest extends TestCase
{
    /** @test */
    public function user_can_show_conversations(): void
    {
        [$user, $conversation] = $this->prepare();

        $response = $this->makeRequest($user, $conversation);

        $this->assertResponse($response);
    }

    /**
     * Prepares for tests.
     *
     * @return array
     */
    private function prepare(): array
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()
            ->hasUsers(1)
            ->hasMessages(2)
            ->create();

        return [$user, $conversation];
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
            ->json('GET', 'conversations/' . $conversation->id);
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

        if ($status !== 200) {
            return;
        }

        $this->assertJsonStructureSnapshot($response);
    }
}
