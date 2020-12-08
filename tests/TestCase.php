<?php

namespace OwowAgency\Gossip\Tests;

use OwowAgency\Snapshots\MatchesSnapshots;
use OwowAgency\Gossip\GossipServiceProvider;
use OwowAgency\LaravelTestResponse\TestResponse;
use OwowAgency\Gossip\Tests\Support\Models\User;
use Orchestra\Testbench\TestCase as BaseTestCase;
use OwowAgency\Gossip\Tests\Support\TestServiceProvider;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTime;
use OwowAgency\LaravelResources\LaravelResourcesServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithTime, MatchesSnapshots;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        config(['gossip.user_model' => User::class]);

        // Refresh the database.
        $this->artisan('migrate:fresh');
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            // We first need to load our testing service provider so that the
            // migrations used for testing are being ran first. Otherwise, we'll
            // get a foreign key constraint because our tables need a
            // relationship to the users table.
            TestServiceProvider::class,
            GossipServiceProvider::class,
            LaravelResourcesServiceProvider::class,
        ];
    }

    /**
     * Create the test response instance from the given response.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return \Illuminate\Testing\TestResponse
     */
    protected function createTestResponse($response)
    {
        return TestResponse::fromBaseResponse($response);
    }
}
