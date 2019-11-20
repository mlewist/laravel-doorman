<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Redsnapper\LaravelDoorman\Models\Contracts\Permission as PermissionContract;
use Redsnapper\LaravelDoorman\Models\Traits\IsGroupedPermission;

/**
 * App\Models\Permission
 *
 * @property string $name
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Permission extends Model implements PermissionContract
{
    use IsGroupedPermission;

    protected $casts = ['active' => 'boolean'];

    protected $fillable = ['name', 'active'];

    public function isActive(): bool
    {
        return $this->active;
    }
}
