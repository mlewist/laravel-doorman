<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Redsnapper\LaravelDoorman\Models\Interfaces\RoleInterface;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Permission;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function roles_can_have_many_permissions()
    {
        $permissionA = factory(Permission::class)->create(["name" => "can-see-the-ground"]);
        $permissionB = factory(Permission::class)->create(["name" => "can-see-the-sky"]);
        /** @var RoleInterface $role */
        $role = factory(Role::class)->create();
        $role->givePermissionTo($permissionA);

        $this->assertTrue($role->hasPermission($permissionA->name));
        $this->assertFalse($role->hasPermission($permissionB->name));

        // Permissions update when adding new permissions to a role
        $role->givePermissionTo($permissionB);
        $this->assertTrue($role->fresh()->hasPermission('can-see-the-sky'));
    }

    /** @test */
    public function permission_which_is_inactive_should_not_grant_access()
    {
        $permission = factory(Permission::class)->create(['active' => false, 'name' => 'can-see-the-ground']);
        $role = factory(Role::class)->create();
        $role->givePermissionTo($permission);
        $this->setAuthRole($role);
        $this->assertFalse($this->authRole->hasPermission("can-see-the-ground"));
    }
}
