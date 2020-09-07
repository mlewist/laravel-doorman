<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Factories\PermissionFactory;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Factories\RoleFactory;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var Role
     */
    protected $testRole;

    /**
     * @var Role
     */
    protected $testRole2;

    /**
     * @var Permission
     */
    protected $testPermission;


    public function setUp(): void
    {
        parent::setUp();

        $this->testRole = RoleFactory::new()->create(['name'=>'Test']);
        $this->testRole2 = RoleFactory::new()->create(['name'=>'Test 2']);
        $this->testPermission = PermissionFactory::new()->create(['name'=>'do-something']);
    }

    /** @test */
    public function can_check_if_permission_has_a_role()
    {
        $this->assertFalse($this->testPermission->hasRole($this->testRole));
        $this->testPermission->assignRole($this->testRole);

        $this->assertTrue($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole->name));
        $this->assertTrue($this->testPermission->hasRole([$this->testRole->name, 'fakeRole']));
        $this->assertTrue($this->testPermission->hasRole($this->testRole->id));
        $this->assertTrue($this->testPermission->hasRole([$this->testRole->id, 'fakeRole']));

    }

    /** @test */
    public function it_can_assign_a_role_using_a_model()
    {
        $this->testPermission->hasRole($this->testRole);
        $this->testPermission->assignRole($this->testRole);
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
    }

    /** @test */
    public function can_assign_a_role_using_an_id()
    {
        $this->testPermission->assignRole($this->testRole->id);
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
    }

    /** @test */
    public function can_assign_a_role_by_name()
    {
        $this->testPermission->assignRole('Test');
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
    }

    /** @test */
    public function can_assign_multiple_roles()
    {
        $this->testPermission->assignRole($this->testRole->id, 'Test 2');
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole2));
    }

    /** @test */
    public function can_assign_multiple_roles_using_an_array()
    {
        $this->testPermission->assignRole([$this->testRole->id, 'Test 2']);
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole2));
    }

    /** @test */
    public function it_does_not_remove_already_associated_roles_when_assigning_new_roles()
    {
        $this->testPermission->assignRole($this->testRole);
        $this->testPermission->assignRole(RoleFactory::new()->create());
        $this->assertTrue($this->testPermission->fresh()->hasRole($this->testRole));
    }

    /** @test */
    public function can_sync_roles_using_a_model()
    {
        $this->testPermission->assignRole($this->testRole);
        $this->testPermission->syncRoles($this->testRole2);
        $this->assertFalse($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole2));
    }

    /** @test */
    public function can_sync_roles_using_a_string()
    {
        $this->testPermission->assignRole($this->testRole);
        $this->testPermission->syncRoles('Test 2');
        $this->assertFalse($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole2));
    }

    /** @test */
    public function it_can_sync_multiple_roles()
    {
        $this->testPermission->syncRoles($this->testRole, $this->testRole2);
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole2));
    }

    /** @test */
    public function it_can_sync_multiple_roles_from_an_array()
    {
        $this->testPermission->syncRoles([$this->testRole, $this->testRole2]);
        $this->assertTrue($this->testPermission->hasRole($this->testRole));
        $this->assertTrue($this->testPermission->hasRole($this->testRole2));
    }


}
