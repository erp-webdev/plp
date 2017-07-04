<?php

namespace eFund\Providers;
use eFund\Log;
use eFund\Role;
use eFund\User;
use eFund\Deduction;
use eFund\Endorser;
use eFund\Guarantor;
use eFund\Ledger;
use eFund\Loan;
use eFund\Payroll;
use eFund\Preference;
use eFund\Terms;
use eFund\Treasury;
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

        // Deduction Model Logs
        Deduction::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Deduction::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Endorser Model Logs
        Endorser::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Endorser::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Guarantor Model Logs
        Guarantor::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Guarantor::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Ledger Model Logs
        Ledger::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Ledger::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Loan Model Logs
        Loan::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Loan::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Payroll Model Logs
        Payroll::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Payroll::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Preference Model Logs
        Preference::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Preference::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Terms Model Logs
        Terms::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Terms::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
        });

        // Treasury Model Logs
        Treasury::deleted(function ($model) {
            $log = new Log();
            $log->write('Delete', $model->getTable(), $model);    
        });

        Treasury::saved(function ($model) {
            $log = new Log();
            $log->write('Update', $model->getTable(), $model);
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
