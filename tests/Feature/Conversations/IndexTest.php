<?php

namespace OwowAgency\Gossip\Tests\Feature\Conversations;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\Support\Models\User;
use OwowAgency\Gossip\Tests\Support\Enumerations\Role;

class IndexTest extends TestCase
{
    /** @test */
    public function user_can_index_conversations(): void
    {
        [$user] = $this->prepare();
        $user->assignRole(Role::ADMIN);

        $response = $this->makeRequest($user);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_cant_index_conversations(): void
    {
        [$user] = $this->prepare();

        $response = $this->makeRequest($user);

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

        Conversation::factory()
            ->count(3)
            ->hasUsers(2)
            ->hasMessages(2)
            ->create();

        return [$user];
    }

    /**
     * Makes a request.
     *
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User  $user
     * @return \Illuminate\Testing\TestResponse
     */
    private function makeRequest(User $user): TestResponse
    {
        return $this->actingAs($user)
            ->json('GET', "/conversations");
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
