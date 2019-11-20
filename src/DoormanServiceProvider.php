<?php

namespace Redsnapper\LaravelDoorman;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;
use Redsnapper\LaravelDoorman\Models\Contracts\PermissionContract;

class DoormanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMigrations();

        $this->publishes([
          __DIR__.'/../config/doorman.php' => config_path('doorman.php'),
        ], 'config');

        $this->publishes([__DIR__.'/database' => database_path()], 'migrations');

        $this->app->singleton(PermissionsRegistrar::class, function ($app) {
            $registrar = new PermissionsRegistrar($app->make(Gate::class));
            $registrar->register();

            return $registrar;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
          __DIR__.'/../config/doorman.php',
          'doorman'
        );

        $this->registerModelBindings();
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function registerModelBindings()
    {
        $config = $this->app->config['doorman.models'];
        $this->app->bind(PermissionContract::class, $config['permission']);
    }

}
