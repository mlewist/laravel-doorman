<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Models\Contracts\UserInterface;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class HasRolesTest extends TestCase
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
    protected $testRole2;

    public function setUp(): void
    {
        parent::setUp();

        $this->testUser = factory(User::class)->create();
        $this->testRole = factory(Role::class)->create(['name'=>'Test']);
        $this->testRole2 = factory(Role::class)->create(['name'=>'Test 2']);
    }

    /** @test */
    public function can_check_if_user_has_a_role()
    {
        $this->assertFalse($this->testUser->hasRole($this->testRole));
        $role = factory(Role::class)->create();
        $this->assertFalse($this->testUser->hasRole($role));
        $this->testUser->assignRole($role);

        $this->assertTrue($this->testUser->hasRole($role));
        $this->assertTrue($this->testUser->hasRole($role->name));
        $this->assertTrue($this->testUser->hasRole([$role->name, 'fakeRole']));
        $this->assertTrue($this->testUser->hasRole($role->id));
        $this->assertTrue($this->testUser->hasRole([$role->id, 'fakeRole']));

    }

    /** @test */
    public function can_remove_a_role()
    {
        $this->testUser->assignRole($this->testRole,$this->testRole2);
        $this->testUser->removeRole($this->testRole2);

        $this->assertTrue($this->testUser->hasRole($this->testRole));
        $this->assertFalse($this->testUser->hasRole($this->testRole2));

    }

    /** @test */
    public function it_can_assign_a_role_using_a_model()
    {
        $this->testUser->hasRole($this->testRole);
        $this->testUser->assignRole($this->testRole);
        $this->assertTrue($this->testUser->hasRole($this->testRole));
    }

    /** @test */
    public function can_assign_a_role_using_an_id()
    {
        $this->testUser->assignRole($this->testRole->id);
        $this->assertTrue($this->testUser->hasRole($this->testRole));
    }

    /** @test */
    public function can_assign_a_role_by_name()
    {
        $this->testUser->assignRole('Test');
        $this->assertTrue($this->testUser->hasRole($this->testRole));
    }

    /** @test */
    public function can_assign_multiple_roles()
    {
        $this->testUser->assignRole($this->testRole->id, 'Test 2');
        $this->assertTrue($this->testUser->hasRole($this->testRole));
        $this->assertTrue($this->testUser->hasRole($this->testRole2));
    }

    /** @test */
    public function can_assign_multiple_roles_using_an_array()
    {
        $this->testUser->assignRole([$this->testRole->id, 'Test 2']);
        $this->assertTrue($this->testUser->hasRole($this->testRole));
        $this->assertTrue($this->testUser->hasRole($this->testRole2));
    }

    /** @test */
    public function it_does_not_remove_already_associated_roles_when_assigning_new_roles()
    {
        $this->testUser->assignRole($this->testRole);
        $this->testUser->assignRole(factory(Role::class)->create());
        $this->assertTrue($this->testUser->fresh()->hasRole($this->testRole));
    }

    /** @test */
    public function can_sync_roles_using_a_model()
    {
        $this->testUser->assignRole($this->testRole);
        $this->testUser->syncRoles($this->testRole2);
        $this->assertFalse($this->testUser->hasRole($this->testRole));
        $this->assertTrue($this->testUser->hasRole($this->testRole2));
    }

    /** @test */
    public function can_sync_roles_using_a_string()
    {
        $this->testUser->assignRole($this->testRole);
        $this->testUser->syncRoles('Test 2');
        $this->assertFalse($this->testUser->hasRole($this->testRole));
        $this->assertTrue($this->testUser->hasRole($this->testRole2));
    }

    /** @test */
    public function it_can_sync_multiple_roles()
    {
        $this->testUser->syncRoles($this->testRole, $this->testRole2);
        $this->assertTrue($this->testUser->hasRole($this->testRole));
        $this->assertTrue($this->testUser->hasRole($this->testRole2));
    }
    /** @test */
    public function it_can_sync_multiple_roles_from_an_array()
    {
        $this->testUser->syncRoles([$this->testRole, $this->testRole2]);
        $this->assertTrue($this->testUser->hasRole($this->testRole));
        $this->assertTrue($this->testUser->hasRole($this->testRole2));
    }



}
