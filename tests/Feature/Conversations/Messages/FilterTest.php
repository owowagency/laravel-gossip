<?php

namespace OwowAgency\Gossip\Tests\Feature\Conversations\Messages;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\Support\Models\User;

class FilterTest extends TestCase
{
    /** @test */
    public function user_can_index_messages_after_creation_date(): void
    {
        [$user, $oldMessage] = $this->prepare();

        $response = $this->makeRequest($user, $oldMessage->conversation, [
            'filter' => [
                'created_after' => now()->toDateTimeString(),
            ],
        ]);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_index_messages_after_updating_date(): void
    {
        [$user, $oldMessage] = $this->prepare('updated_at');

        $response = $this->makeRequest($user, $oldMessage->conversation, [
            'filter' => [
                'updated_after' => now()->toDateTimeString(),
            ],
        ]);

        $this->assertResponse($response);
    }

    /**
     * Prepares for tests.
     *
     * @param  string  $field
     * @return array
     */
    private function prepare(string $field = 'created_at'): array
    {
        $user = User::factory()->create();

        $conversation = Conversation::factory()->create();

        // Add user as participant.
        $conversation->users()->attach($user);

        $oldMessage = Message::factory()->create([
            'conversation_id' => $conversation->id,
            $field => now()->subWeek(),
        ]);

        $newMessage = Message::factory()->create([
            'conversation_id' => $conversation->id,
            $field => now()->addWeek(),
        ]);

        return [$user, $oldMessage];
    }

    /**
     * Makes a request.
     *
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User  $user
     * @param  \OwowAgency\Gossip\Models\Conversation  $conversation
     * @param  array  $params
     * @return \Illuminate\Testing\TestResponse
     */
    private function makeRequest(
        User $user,
        Conversation $conversation,
        array $params = []
    ): TestResponse {
        $url = "/conversations/{$conversation->id}/messages?" . http_build_query($params);

        return $this->actingAs($user)
            ->json('GET', $url);
    }

    /**
     * Asserts a response.
     *
     * @param  \Illuminate\Testing\TestResponse  $response
     * @param  int  $status
     * @return void
     */
    private function assertResponse(
        TestResponse $response,
        int $status = 200
    ): void {
        $response->assertStatus($status);

        if ($status === 200) {
            $this->assertJsonStructureSnapshot($response);
        }
    }
}
