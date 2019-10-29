<?php

namespace Redsnapper\LaravelDoorman\Models\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Redsnapper\LaravelDoorman\PermissionsRegistrar;

trait HasPermissions
{
    /**
     * @return BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(app(PermissionsRegistrar::class)->getPermissionClass());
    }
}
