<?php

namespace OwowAgency\Gossip\Tests;

use OwowAgency\Gossip\ServiceProvider;
use OwowAgency\Snapshots\MatchesSnapshots;
use OwowAgency\LaravelTestResponse\TestResponse;
use OwowAgency\Gossip\Tests\Support\Models\User;
use Spatie\Permission\PermissionServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use OwowAgency\Gossip\Tests\Support\TestServiceProvider;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTime;
use OwowAgency\LaravelResources\LaravelResourcesServiceProvider;
use OwowAgency\Gossip\Tests\Support\Database\Seeders\PermissionSeeder;

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

        // Publish the vendor files. This will register the migrations of all
        // dependencies.
        $this->artisan('vendor:publish', [
            '--all' => true,
        ]);

        // Refresh the database.
        $this->artisan('migrate:fresh');

        $this->seed(PermissionSeeder::class);
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
            ServiceProvider::class,
            LaravelResourcesServiceProvider::class,
            PermissionServiceProvider::class,
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
