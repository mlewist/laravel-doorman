<?php

namespace Redsnapper\LaravelDoorman;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

class DoormanAuthServiceProvider extends AuthServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @param  PermissionsRegistrar  $permissionsRegistrar
     */
    public function boot(PermissionsRegistrar $permissionsRegistrar)
    {
        $permissionsRegistrar->register();

        $this->registerPolicies();
    }
}
