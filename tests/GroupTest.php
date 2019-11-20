<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Exceptions\CurrentGroupNotSetException;
use Redsnapper\LaravelDoorman\Models\Contracts\GroupedPermissionContract;
use Redsnapper\LaravelDoorman\Models\Contracts\RoleInterface;
use Redsnapper\LaravelDoorman\Models\Contracts\UserInterface;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped\Group;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped\User;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped\Permission;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Role;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up the environment.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('doorman.role_class', Role::class);

        $app['config']->set('doorman.models.permission', Permission::class);
        $app['config']->set('doorman.user_class', User::class);
        $app['config']->set('doorman.uses_groups', true);
        $app['config']->set('doorman.group_name', 'Group');
        $app['config']->set('doorman.group_class', Group::class);
    }

    protected function signIn($user = null, $region = null): UserInterface
    {
        if ($this->authUser) {
            return $this->authUser;
        }

        $this->authUser = $user ?: factory(User::class)->create();
        $this->actingAs($this->authUser);

        return $this->authUser;
    }

    /** @test */
    public function users_can_be_grouped()
    {
        $group = factory(Group::class)->create(["name" => "Arsenal"]);

        $this->signIn();

        $this->assertTrue($this->authUser->groups->isEmpty());
        $this->authUser->groups()->sync($group);
        $this->authUser->refresh();
        $this->assertTrue($this->authUser->groups->isNotEmpty());
    }

    /**
     * @throws \Exception
     * @test
     */
    public function a_user_must_have_a_current_group_set()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create(["name" => "Arsenal"]);
        $activityA = factory(Permission::class)->create(["name" => "Play out from the back"]);

        /** @var RoleInterface $role */
        $role = factory(Role::class)->create(["name" => "Centre back"]);
        $role->givePermissionTo($activityA);
        $this->signIn($user);
        $this->authUser->assignRole($role);

        $this->expectException(CurrentGroupNotSetException::class);
        $user->hasPermissionTo($activityA->name);

        $user->setCurrentGroup($group);
        $user->refresh();
        $this->assertTrue($this->authUser->hasPermissionTo($activityA->name));
    }

    /** @test */
    public function permissions_can_have_groups()
    {
        $arsenal = factory(Group::class)->create(["name" => "Arsenal"]);
        $liverpool = factory(Group::class)->create(["name" => "Liverpool"]);
        /** @var GroupedPermissionContract $permission */
        $permission = factory(Permission::class)->create(["name" => "Play negative football"]);

        $permission->groups()->attach($arsenal);

        $this->assertTrue($permission->groups->isNotEmpty());
        $this->assertTrue($permission->allowsGroup($arsenal));
        $this->assertFalse($permission->allowsGroup($liverpool));
    }

    /** @test */
    public function if_a_permission_has_no_groups_then_all_groups_can_access_the_permission()
    {
        $arsenal = factory(Group::class)->create(["name" => "Arsenal"]);
        $liverpool = factory(Group::class)->create(["name" => "Liverpool"]);
        /** @var GroupedPermissionContract $permission */
        $permission = factory(Permission::class)->create(["name" => "Play football"]);

        $this->assertTrue($permission->allowsGroup($arsenal));
        $this->assertTrue($permission->allowsGroup($liverpool));
    }

    /** @test */
    public function if_permissions_have_a_group_or_groups_then_users_outside_the_groups_cant_access_the_permission()
    {
        $arsenal = factory(Group::class)->create(["name" => "Arsenal"]);
        $manUtd = factory(Group::class)->create(["name" => "Man Utd"]);

        $manager = factory(Role::class)->create(["name" => "Football manager"]);
        $wenger = factory(User::class)->create(["name" => "Wenger"]);
        $mourinho = factory(User::class)->create(["name" => "Mourinho"]);

        $wenger->assignRole($manager);
        $wenger->groups()->syncWithoutDetaching($arsenal->getKey());
        $wenger->setCurrentGroup($arsenal);

        $mourinho->assignRole($manager);
        $mourinho->groups()->syncWithoutDetaching($manUtd->getKey());
        $mourinho->setCurrentGroup($manUtd);

        $permission = factory(Permission::class)->create(["name" => "Have class"]);

        $manager->givePermissionTo($permission);
        $permission->groups()->attach($arsenal);

        $this->assertTrue($wenger->hasPermissionTo("Have class"));
        $this->assertFalse($mourinho->hasPermissionTo("Have class"));
    }
}
