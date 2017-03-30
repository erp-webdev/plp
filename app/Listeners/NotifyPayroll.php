<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;

use DB;
use eFund\Events\EndorsementApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyPayroll  extends EmailController
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
     * @param  EndorsementApproved  $event
     * @return void
     */
    public function handle(EndorsementApproved $event)
    {

        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $event->loan, 'employee' => $employee];

            $this->send($employee->employee_id, config('preferences.notif_subjects.created', 'Loan Application Notification'), 'emails.payroll_verify', $args, $cc = '');
            
        }

    }
}
