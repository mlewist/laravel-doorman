<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Redsnapper\LaravelDoorman\DoormanServiceProvider;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class TestCase extends OrchestraTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->withFactories(realpath(__DIR__.'/Fixtures/database/factories'));
    }

    /**
     * @param  Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [DoormanServiceProvider::class];
    }

    /**
     * Set up the environment.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('auth.providers.users.model', User::class);
    }

}
