<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Redsnapper\LaravelDoorman\Models\Interfaces\RoleInterface;
use Redsnapper\LaravelDoorman\Models\Interfaces\UserInterface;
use Redsnapper\LaravelDoorman\DoormanAuthServiceProvider;
use Redsnapper\LaravelDoorman\DoormanServiceProvider;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Permission;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class TestCase extends OrchestraTestCase
{
    /** @var RoleInterface */
    protected $authRole;

    /** @var UserInterface */
    protected $authUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->withFactories(realpath(__DIR__.'/Fixtures/database/factories'));

    }

    /**
     * @param  Application  $app
     * @return array
     *
     */
    protected function getPackageProviders($app)
    {
        return [DoormanServiceProvider::class, DoormanAuthServiceProvider::class];
    }

    /**
     * Set up the environment.
     *
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('doorman.role_class', Role::class);
        $app['config']->set('doorman.permission_class', Permission::class);
        $app['config']->set('doorman.user_class', User::class);
    }

    protected function signIn($user = null,$region =null): UserInterface
    {
        if ($this->authUser) {
            return $this->authUser;
        }

        $this->authUser = $user ?: factory(User::class)->create();
        $this->actingAs($this->authUser);

        return $this->authUser;
    }

    protected function withPermissions(...$permissions)
    {
        $role = $this->getAuthRole();

        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        return $this;
    }

    /**
     * Get role for this user
     *
     * @return RoleInterface
     */
    private function getAuthRole(): RoleInterface
    {
        if ($this->authRole) {
            return $this->authRole;
        }

        $this->setAuthRole();

        return $this->authRole;
    }

    /**
     * @param  Role|null  $role
     * @return mixed|Role
     */
    public function setAuthRole(Role $role = null)
    {
        if (is_null($role)) {
            $role = factory(Role::class)->create();
        }

        $this->signIn()->assignRole($role);

        $this->authRole = $role;

        return $this->authRole;
    }
}
