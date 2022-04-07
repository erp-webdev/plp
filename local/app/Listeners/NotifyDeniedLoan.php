<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\NotificationController;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Employee;


use eFund\Events\LoanDenied;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyDeniedLoan extends EmailController
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LoanDenied  $event
     * @return void
     */
    public function handle(LoanDenied $event)
    {
        $notif = new NotificationController();
        $notif->notifyAppDenied($event->loan);


        $args = ['loan' => $event->loan];

        $employee = Employee::where('EmpID', $event->loan->EmpID)
        ->where('DBNAME', $event->loan->DBNAME)
        ->first();

        if(isset($employee->EmailAdd))
            $this->send($employee, config('preferences.notif_subjects.denied', 'Loan Application Notification'), 'emails.denied', $args, $cc = '');
    }
}
