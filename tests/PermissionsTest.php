<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Permission;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_tell_when_a_permission_has_been_granted()
    {
        $user = $this->signIn();

        $activityA = factory(Permission::class)->create(['name' =>'can-see-the-ground']);
        $activityB = factory(Permission::class)->create(['name'=>'can-see-the-sky']);

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

        $activityA = factory(Permission::class)->create(['name' =>'can-swim-fast']);
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




}
