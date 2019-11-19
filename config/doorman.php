<?php

return [
  'models' => [
    'permission' => \Redsnapper\LaravelDoorman\Models\Permission::class
  ],
  'role_class' => '',
  'permission_class' => '',
  'user_class' => \Illuminate\Foundation\Auth\User::class,
  'uses_groups' => false,
  'group_name' => 'Group',
  'group_class' => '',
];
