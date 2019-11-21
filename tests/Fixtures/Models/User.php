<?php

namespace Redsnapper\LaravelDoorman\Tests\Fixtures\Models;

use Illuminate\Foundation\Auth\Access\Authorizable;
use Redsnapper\LaravelDoorman\Models\Traits\HasPermissionsViaRoles;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasPermissionsViaRoles, Authorizable, \Illuminate\Auth\Authenticatable;
}
