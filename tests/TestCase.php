<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Redsnapper\LaravelDoorman\Models\Contracts\Role as RoleContract;
use Redsnapper\LaravelDoorman\Models\Contracts\UserInterface;
use Redsnapper\LaravelDoorman\DoormanServiceProvider;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class TestCase extends OrchestraTestCase
{
    /** @var RoleContract */
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
     * @return Role
     */
    private function getAuthRole(): RoleContract
    {
        if ($this->authRole) {
            return $this->authRole;
        }

        $this->setAuthRole();

        return $this->authRole;
    }

    /**
     * @param  RoleContract|null  $role
     * @return mixed|RoleContract
     */
    public function setAuthRole(RoleContract $role = null)
    {
        if (is_null($role)) {
            $role = factory(Role::class)->create();
        }

        $this->signIn()->assignRole($role);

        $this->authRole = $role;

        return $this->authRole;
    }
}
