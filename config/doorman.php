<?php

return [

  'models'     => [

      /*
      |--------------------------------------------------------------------------
      | Permission model
      |--------------------------------------------------------------------------
      |
      | When using the "HasPermissions" trait from this package, we need to know which
      | Eloquent model should be used to retrieve your permissions.
      |
      | This model needs to implement the
      | Redsnapper\LaravelDoorman\Models\Contracts\PermissionContract
      |
      */
      'permission' => \Redsnapper\LaravelDoorman\Models\Permission::class,

      /*
     |--------------------------------------------------------------------------
     | Permission model
     |--------------------------------------------------------------------------
     |
     | When using the "HasRoles" trait from this package, we need to know which
     | Eloquent model should be used to retrieve your roles.
     |
     | This model needs to implement the
     | Redsnapper\LaravelDoorman\Models\Contracts\RoleContract
     |
     */
      'role' => \Redsnapper\LaravelDoorman\Models\Role::class

  ],

  /*
    |--------------------------------------------------------------------------
    | Migrations
    |--------------------------------------------------------------------------
    |
    | This option defines whether the migrations will run when from the package.
    | If the migrations are published then those migrations will run rather than
    | the ones defined in the package. If you don't want any migrations to run then
    | set this to false
    |
    */
  'migrations' => true,

  'uses_groups' => false,
  'group_name'  => 'Group',
  'group_class' => '',
];
