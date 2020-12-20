<?php

namespace OwowAgency\Gossip;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * The name of the package.
     *
     * @var string
     */
    private string $name = 'gossip';
    
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadTranslationsFrom(__DIR__.'/../translations', $this->name);

        $this->registerPublishableFiles();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . "/../config/$this->name.php", $this->name);
    }

    /**
     * Register files to be published by the publish command.
     *
     * @return void
     */
    protected function registerPublishableFiles(): void
    {
        $this->publishes(
            [
                __DIR__ . "/../config/$this->name.php" => config_path("$this->name.php"),
            ],
            [$this->name, "$this->name.config", 'config'],
        );

        $this->publishes(
            [
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ],
            [$this->name, "$this->name.migrations", 'migrations'],
        );

        $this->publishes(
            [
                __DIR__ . '/Resources' => app_path('Http/Resources'),
            ],
            [$this->name, "$this->name.http_resources", 'resources'],
        );

        $this->publishes(
            [
                __DIR__ . '/Policies' => app_path('Policies'),
            ],
            [$this->name, "$this->name.policies", 'policies'],
        );
    }
}
