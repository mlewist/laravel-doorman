<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Models\Contracts\UserInterface;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var User
     */
    protected $testUser;

    /**
     * @var Role
     */
    protected $testRole;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = factory(User::class)->create();
        $this->testRole = factory(Role::class)->create(['name'=>'Test']);
    }

    /** @test */
    public function roles_can_have_permissions()
    {
        $permissionA = factory(Permission::class)->create(["name" => "can-see-the-ground"]);
        $permissionB = factory(Permission::class)->create(["name" => "can-see-the-sky"]);
        /** @var Role $role */
        $role = factory(Role::class)->create();
        $role->givePermissionTo($permissionA);

        $this->assertTrue($role->hasPermission($permissionA->name));
        $this->assertFalse($role->hasPermission($permissionB->name));

        // Permissions update when adding new permissions to a role
        $role->givePermissionTo($permissionB);
        $this->assertTrue($role->fresh()->hasPermission('can-see-the-sky'));
    }

    /** @test */
    public function a_role_can_have_multiple_users()
    {
        /** @var Role $role */
        $role = factory(Role::class)->create(['name' => 'Dodgy Characters']);

        /** @var UserInterface $user */
        $user = factory(User::class)->create(['name' => 'Nigel_Farage']);
        $user2 = factory(User::class)->create(['name' => 'Boris_Johnson']);

        $this->assertTrue($role->users->isEmpty());

        $role->users()->sync([$user->getKey(), $user2->getKey()]);

        $role->refresh();

        $this->assertTrue($role->users->isNotEmpty());
        $this->assertEquals(2, $role->users()->count());
    }


    /** @test */
    public function permissions_on_multiple_roles_all_apply_to_user()
    {
        $permission = factory(Permission::class)->create(['name' => 'act-superbly']);
        $permission2 = factory(Permission::class)->create(['name' => 'look-fantastic']);

        /** @var Role $role */
        $role = factory(Role::class)->create(['name' => 'Good Actors']);
        $role2 = factory(Role::class)->create(['name' => 'Handsome Actors']);

        $role->givePermissionTo($permission);
        $role2->givePermissionTo($permission2);

        /** @var UserInterface $user */
        $user = factory(User::class)->create(['name' => 'Leo_DiCaprio']);

        $user->assignRole($role);
        $user->assignRole($role2);

        $this->signIn($user);

        $this->assertTrue($user->can('act-superbly'));
        $this->assertTrue($user->can('look-fantastic'));
    }
}
