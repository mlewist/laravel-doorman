<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExistException;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Models\Role;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_tell_when_a_permission_has_been_granted()
    {
        $user = $this->signIn();

        $activityA = factory(Permission::class)->create(['name' => 'can-see-the-ground']);
        $activityB = factory(Permission::class)->create(['name' => 'can-see-the-sky']);

        $role = factory(Role::class)->create(['name' => 'Person with bad neck']);
        $role->givePermissionTo($activityA);
        $user->assignRole($role);

        $this->assertTrue($user->hasPermissionTo($activityA->name));
        $this->assertFalse($user->hasPermissionTo($activityB->name));
    }

    /** @test */
    public function permissions_can_be_taken_away()
    {
        $user = $this->signIn();

        $activityA = factory(Permission::class)->create(['name' => 'can-swim-fast']);
        $role = factory(Role::class)->create(['name' => 'Person with big feet']);
        $role->givePermissionTo($activityA);
        $user->assignRole($role);
        $this->assertTrue($user->hasPermissionTo($activityA->name));

        $role->removePermissionTo($activityA);
        $this->assertFalse($user->hasPermissionTo($activityA->name));
    }

    /** @test */
    public function it_throws_an_exception_when_given_a_permission_that_does_not_exist()
    {
        $this->expectException(Exception::class);
        $role = factory(Role::class)->create();
        $role->givePermissionTo('foo');
    }

    /** @test */
    public function permissions_can_be_checked_with_laravel_auth_guard_can()
    {
        $activityA = factory(Permission::class)->create(['name' => 'can-fly']);
        $activityB = factory(Permission::class)->create(['name' => 'can-run']);
        $role = factory(Role::class)->create(['name' => 'Birdman']);
        $role->givePermissionTo($activityA);

        $user = $this->signIn();
        $this->setAuthRole($role);

        $this->assertTrue(auth()->user()->can('can-fly'));
        $this->assertFalse(auth()->user()->can('can-run'));
    }

    /** @test */
    public function it_throws_a_permission_does_not_exist_for_non_existant_permissions()
    {
        $this->signIn();
        $this->expectException(PermissionDoesNotExistException::class);

        $this->authUser->hasPermissionTo('non-existent');
    }
}
