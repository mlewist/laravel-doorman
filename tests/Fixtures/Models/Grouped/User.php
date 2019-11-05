<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models\Grouped;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Redsnapper\LaravelDoorman\Models\Interfaces\GroupedUserInterface;
use Redsnapper\LaravelDoorman\Models\Traits\IsGroupedUser;

class User extends \Illuminate\Foundation\Auth\User implements GroupedUserInterface
{
    use IsGroupedUser,
        Authorizable,
        \Illuminate\Auth\Authenticatable;
}
