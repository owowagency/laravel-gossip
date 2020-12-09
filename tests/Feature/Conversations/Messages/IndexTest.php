<?php

namespace OwowAgency\Gossip\Tests\Feature\Conversations\Messages;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\Support\Models\User;

class IndexTest extends TestCase
{
    /** @test */
    public function user_can_index_messages_of_a_conversation(): void
    {
        [$user, $conversation] = $this->prepare();

        $response = $this->makeRequest($user, $conversation);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_index_and_mark_messages_as_read(): void
    {
        [$user, $conversation] = $this->prepare();

        $response = $this->makeRequest($user, $conversation, [
            'mark_messages_as_read' => true,
        ]);

        // The "current_user_read_message" attribute should be a timestamp. This
        // is validated in the snapshot.
        $this->assertResponse($response);
    }

    /** @test */
    public function user_cant_index_messages_of_other_conversation(): void
    {
        [$user, $conversation] = $this->prepare();

        $other = User::factory()->create();

        $response = $this->makeRequest($other, $conversation);

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

        $conversation = Conversation::factory()
            ->hasUsers(2)
            ->hasMessages(30, [
                'user_id' => $user->id,
            ])
            ->create();

        // Add user as participant.
        $conversation->users()->attach($user);

        return [$user, $conversation];
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
    private function assertResponse(TestResponse $response, int $status = 200): void
    {
        $response->assertStatus($status);

        if ($status === 200) {
            $this->assertJsonStructureSnapshot($response);
        }
    }
}
