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

    public function setUp(): void
    {
        parent::setUp();
        $this->testUser = factory(User::class)->create();
        $this->testRole = factory(Role::class)->create(['name'=>'Test']);
        $this->testPermission = factory(Permission::class)->create(['name'=>'Test']);
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

        $this->testRole->givePermissionTo(['Test',$permission]);
        $this->assertTrue($this->testRole->hasPermissionTo('Test'));
        //$this->assertTrue($this->testRole->hasPermissionTo($permission));
    }


}
