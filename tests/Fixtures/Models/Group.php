<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Redsnapper\LaravelDoorman\Models\Interfaces\GroupInterface;
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
