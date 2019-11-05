# Laravel Version Control

This package provides traits to use to quickly scaffold role based permissions to your laravel project.

## Installation

Composer require redsnapper/laravel-doorman // tbc

Publish the package and then set your permission and role models. They must implement the PermissionInterface and RoleInterface.

Use the traits provided... IsPermission and IsRole. 

Your User model, must use the trait IsUser and implement UserInterface and the standard Laravel Authorizable contract.

### Using groups

Set uses_groups to true in the config file  
Then also set your group_name (Team, Groups, Region etc) and group_class  
You should adjust the table and migration file names to suit, if changing from the default of 'group/s'

You will need a Group class which implements GroupInterface.  
You can use the IsGroup trait to meet the interface requirements.  

Your User model should implement GroupedUserInterface, which itself extends the GroupInterface.  
You can use the 'IsGroupedUser' trait to meet the interface requirements.

Your Permission class should now implemented GroupedPermissionInterface instead, this extends PermissionInterface.  
You can use the IsGroupedPermission trait to meet the interface requirements. 

## Migrations

The other thing that will be published are the migration files. Dont forget to run migrate. This will create tables:

permission_role
permissions
roles
role_user

Optionally, if uses_groups is turned on in config, it will also create

groups
group_user
group_permissions

It's expected that you already have your user table.

## Checking permissions

That should be it as Laravel auto registers providers. 
To check whether the doorman will let a user through, use $user->hasPermissionTo(string $permission)
Utilise this in your policies / when checking permissions.
