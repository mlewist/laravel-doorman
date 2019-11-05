<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Redsnapper\LaravelDoorman\Models\Interfaces\PermissionInterface;
use Redsnapper\LaravelDoorman\Models\Traits\IsPermission;

/**
 * App\Models\Permission
 *
 * @property string $name
 * @property int $active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Permission extends Model implements PermissionInterface
{
    use IsPermission;

    protected $casts = ['active' => 'boolean'];

    protected $fillable = ['name','active'];

    public function isActive(): bool
    {
        return $this->active;
    }
}
