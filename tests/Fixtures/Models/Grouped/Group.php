<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped;

use Illuminate\Database\Eloquent\Model;
use Redsnapper\LaravelDoorman\Models\Contracts\GroupInterface;
use Redsnapper\LaravelDoorman\Models\Traits\IsGroup;

/**
 * App\Models\Group
 *
 * @property string $name
 */
class Group extends Model implements GroupInterface
{
    use IsGroup;
}
