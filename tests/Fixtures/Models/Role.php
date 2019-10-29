<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Redsnapper\LaravelDoorman\Models\Interfaces\RoleInterface;
use Redsnapper\LaravelDoorman\Models\Traits\IsDoormanRole;

/**
 * App\Models\Role
 *
 * @property string $code
 * @property string $name
 * @property int $level
 * @property string $comment
 */
class Role extends Model implements RoleInterface
{
    use IsDoormanRole;
}
