<?php

namespace Redsnapper\LaravelDoorman\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Access\Gate;
use Redsnapper\LaravelDoorman\Models\Permission;
use Redsnapper\LaravelDoorman\Models\Role;
use Redsnapper\LaravelDoorman\Tests\Fixtures\Models\User;

class GateTest extends TestCase
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
    public function it_can_determine_if_a_user_does_not_have_a_permission()
    {
        $this->assertFalse($this->testUser->can('do-something'));
    }

    /** @test */
    public function it_allows_other_gate_before_callbacks_to_run_if_a_user_does_not_have_a_permission()
    {
        $this->assertFalse($this->testUser->can('do-something'));
        app(Gate::class)->before(function () {
            return true;
        });
        $this->assertTrue($this->testUser->can('do-something'));
    }

    /** @test */
    public function it_can_determine_if_a_user_has_a_permission_through_roles()
    {
        factory(Permission::class)->create(['name'=>'existing-permission']);

        $this->testRole->givePermissionTo($this->testPermission);
        $this->testUser->assignRole($this->testRole);
        $this->assertTrue($this->testUser->can('do-something'));
        $this->assertFalse($this->testUser->can('existing-permission'));
    }



}
