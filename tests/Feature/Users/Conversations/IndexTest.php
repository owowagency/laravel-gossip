<?php

namespace OwowAgency\Gossip\Tests\Feature\Users\Conversations;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\Support\Models\User;
use OwowAgency\Gossip\Tests\Support\Enumerations\Role;

class IndexTest extends TestCase
{
    /** @test */
    public function admin_can_index_conversations_of_other(): void
    {
        [$user] = $this->prepare();

        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        $response = $this->makeRequest($admin, $user);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_index_conversations(): void
    {
        [$user] = $this->prepare();

        $response = $this->makeRequest($user);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_cant_index_conversations_of_others(): void
    {
        [$user] = $this->prepare();

        $other = User::factory()->create();

        $response = $this->makeRequest($user, $other);

        $this->assertResponse($response, 403);
    }

    /**
     * Prepares for tests.
     *
     * @return array
     */
    private function prepare(): array
    {
        $user = User::factory()
            ->hasConversations(3)
            ->create();

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
     * @param  \OwowAgency\Gossip\Tests\Support\Models\User|null  $other
     * @return \Illuminate\Testing\TestResponse
     */
    private function makeRequest(User $user, User $other = null): TestResponse
    {
        $view = $other ?: $user;

        return $this->actingAs($user)
            ->json('GET', "/users/{$view->id}/conversations");
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
