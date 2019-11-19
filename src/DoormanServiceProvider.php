<?php

namespace Redsnapper\LaravelDoorman;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class DoormanServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PermissionsRegistrar::class, function ($app) {
            return new PermissionsRegistrar($app->make(Gate::class));
        });

        $this->mergeConfigFrom(
          __DIR__.'/../config/doorman.php',
          'doorman'
        );


    }

    public function boot()
    {
        $this->registerMigrations();

        $this->publishes([
          __DIR__.'/../config/doorman.php' => config_path('doorman.php'),
        ], 'config');


        $this->publishes([__DIR__.'/database' => database_path()], 'doorman-migrations');
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

}
