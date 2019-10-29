# Laravel Version Control

This package provides traits to use to quickly scaffold role based permissions to your laravel project.

## Installation

Composer require redsnapper/laravel-doorman // tbc

Publish the package and then set your permission and role models. They must implement the PermissionInterface and RoleInterface.

Use the traits provided... IsDoormanPermission and IsDoormanRole. 

Your User model, must use the trait GoesThroughDoorman and implement UserInterface and the standard Laravel Authorizable contract.

## Migrations

The other thing that will be published are the migration files. Dont forget to run migrate. This will create/update tables:

permission_role
permissions
roles
role_user  

## Checking permissions

That should be it as Laravel auto registers providers. To check whether the doorman will let a user through, just use the normal laravel auth methods.
