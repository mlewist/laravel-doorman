<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Redsnapper\LaravelDoorman\Models\Contracts\UserInterface;
use Redsnapper\LaravelDoorman\Models\Traits\IsUser;

class User extends \Illuminate\Foundation\Auth\User implements UserInterface
{
    use IsUser,
        Authorizable,
        \Illuminate\Auth\Authenticatable;
}
