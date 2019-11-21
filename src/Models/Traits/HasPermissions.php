<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissions
{


    /**
     * @param  string  $permission
     * @return bool
     */
    public function hasPermissionTo(string $permission): bool
    {
        /** @var Permission $permission */
        $permission = app(PermissionsRegistrar::class)->getPermissionClass()->findByName($permission);

        return ($this->hasPermission($permission));
    }




}
