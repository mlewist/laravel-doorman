<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Exceptions\PermissionDoesNotExist;
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

    /**
     * @var Role
     */
    protected $testPermission;

    public function setUp(): void
    {
        parent::setUp();
        $this->testUser = factory(User::class)->create();
        $this->testRole = factory(Role::class)->create(['name'=>'Test']);
        $this->testPermission = factory(Permission::class)->create(['name'=>'do-something']);
    }

    /** @test */
    public function a_role_can_have_users()
    {
        $this->testUser->assignRole($this->testRole);
        $this->assertTrue($this->testRole->users->first()->is($this->testUser));
    }

    /** @test */
    public function can_be_given_a_permission()
    {
        $permissionA = factory(Permission::class)->create(["name" => "can-see-the-ground"]);
        $this->testRole->givePermissionTo($permissionA);
        $this->assertTrue($this->testRole->hasPermission($permissionA->name));
    }

    /** @test */
    public function it_throws_an_exception_when_given_a_permission_that_does_not_exist()
    {
        $this->expectException(PermissionDoesNotExist::class);
        $this->testRole->givePermissionTo('act-superbly');
    }

    /** @test */
    public function can_be_given_multiple_permissions_using_an_array()
    {
        $permission = factory(Permission::class)->create();

        $this->testRole->givePermissionTo(['do-something',$permission]);
        $this->assertTrue($this->testRole->hasPermissionTo('do-something'));
        $this->assertTrue($this->testRole->hasPermissionTo($permission));
    }

    /** @test */
    public function it_can_be_given_multiple_permissions_using_multiple_arguments()
    {
        $permission = factory(Permission::class)->create();

        $this->testRole->givePermissionTo('do-something', $permission);
        $this->assertTrue($this->testRole->hasPermissionTo('do-something'));
        $this->assertTrue($this->testRole->hasPermissionTo($permission));
    }

    /** @test */
    public function it_can_sync_permissions()
    {
        $permission = factory(Permission::class)->create();
        $this->testRole->givePermissionTo('do-something');
        $this->testRole->syncPermissions($permission);
        $this->assertFalse($this->testRole->hasPermissionTo('do-something'));
        $this->assertTrue($this->testRole->hasPermissionTo($permission));
    }

    /** @test */
    public function it_throws_an_exception_when_syncing_permissions_that_do_not_exist()
    {
        $this->expectException(PermissionDoesNotExist::class);
        $this->testRole->syncPermissions('permission-does-not-exist');
    }

    /** @test */
    public function it_can_remove_a_permission()
    {
        $this->testRole->givePermissionTo('do-something');
        $this->assertTrue($this->testRole->hasPermissionTo('do-something'));
        $this->testRole->removePermissionTo('do-something');
        $this->assertFalse($this->testRole->hasPermissionTo('do-something'));
    }

    /** @test */
    public function it_can_give_and_remove_multiple_permissions()
    {
        $permission = factory(Permission::class)->create();
        $this->testRole->givePermissionTo(['do-something', $permission]);
        $this->assertEquals(2, $this->testRole->permissions()->count());
        $this->testRole->removePermissionTo(['do-something', $permission]);
        $this->assertEquals(0, $this->testRole->permissions()->count());
    }

    /** @test */
    public function it_returns_false_if_it_does_not_have_the_permission()
    {
        $this->assertFalse($this->testRole->hasPermissionTo('do-something'));
    }

    /** @test */
    public function it_throws_an_exception_if_the_permission_does_not_exist()
    {
        $this->expectException(PermissionDoesNotExist::class);
        $this->testRole->hasPermissionTo('doesnt-exist');
    }

    /** @test */
    public function it_does_not_remove_already_associated_permissions_when_assigning_new_permissions()
    {
        $permission = factory(Permission::class)->create();
        $this->testRole->givePermissionTo('do-something');
        $this->testRole->givePermissionTo($permission);
        $this->assertTrue($this->testRole->hasPermissionTo('do-something'));
    }

    /** @test */
    public function it_does_not_throw_an_exception_when_assigning_a_permission_that_is_already_assigned()
    {
        $this->testRole->givePermissionTo('do-something');
        $this->testRole->givePermissionTo('do-something');
        $this->assertTrue($this->testRole->hasPermissionTo('do-something'));
    }



}
