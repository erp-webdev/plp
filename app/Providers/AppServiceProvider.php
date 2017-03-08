<?php

namespace eFund\Providers;
use eFund\Log;
use eFund\Role;
use eFund\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider 
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        User::deleted(function ($user) {
            $log = new Log();
            $log->write('Delete', $user->getTable(), $user);
        });

        User::saved(function ($user) {
            $log = new Log();
            $log->write('Update', $user->getTable(), $user);
        });

        // Role Model Logs
        Role::deleted(function ($role) {
            $log = new Log();
            $log->write('Delete', $role->getTable(), $role);    
        });

        Role::saved(function ($role) {
            $log = new Log();
            $log->write('Update', $role->getTable(), $role);
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
