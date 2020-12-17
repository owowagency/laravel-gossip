<?php

namespace OwowAgency\Gossip\Tests\Feature\Conversations\Users;

use Illuminate\Testing\TestResponse;
use OwowAgency\Gossip\Models\Message;
use OwowAgency\Gossip\Tests\TestCase;
use OwowAgency\Gossip\Models\Conversation;
use OwowAgency\Gossip\Tests\Support\Models\User;
use OwowAgency\Gossip\Tests\Support\Enumerations\Role;

class IndexTest extends TestCase
{
    /** @test */
    public function admin_can_index_users_of_a_conversation(): void
    {
        [$user, $conversation] = $this->prepare();

        $admin = User::factory()->create();
        $admin->assignRole(Role::ADMIN);

        $response = $this->makeRequest($admin, $conversation);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_can_index_users_of_a_conversation(): void
    {
        [$user, $conversation] = $this->prepare();

        $response = $this->makeRequest($user, $conversation);

        $this->assertResponse($response);
    }

    /** @test */
    public function user_cant_index_users_of_other_conversation(): void
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
    private function makeRequest(User $user, Conversation $conversation): TestResponse
    {
        return $this->actingAs($user)
            ->json('GET', "/conversations/{$conversation->id}/users");
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
