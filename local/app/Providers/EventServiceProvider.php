<?php

namespace eFund\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        
        'eFund\Events\LoanCreated' => [
            'eFund\Listeners\NotifyEndorser',
        ],
        'eFund\Events\GuarantorApproved' => [
            'eFund\Listeners\NotifyPayroll',
        ],
        'eFund\Events\EndorsementApproved' => [
            'eFund\Listeners\NotifyGuarantor',
        ],
        'eFund\Events\PayrollVerified' => [
            'eFund\Listeners\NotifyOfficer',
        ],
        'eFund\Events\LoanApproved' => [
            'eFund\Listeners\NotifyTreasury',
        ],
        'eFund\Events\CheckSigned' => [
            'eFund\Listeners\SendSignedCheckNotif',
        ],
        'eFund\Events\CheckReleased' => [
            'eFund\Listeners\NotifyOnCheckReleased',
        ],
        'eFund\Events\LoanPaid' => [
            'eFund\Listeners\NotifyPaidEmployee',
        ],
        'eFund\Events\LoanDenied' => [
            'eFund\Listeners\NotifyDeniedLoan',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
