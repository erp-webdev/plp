<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;
use eFund\Employee;

use DB;
use eFund\Events\PayrollVerified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOfficer extends EmailController
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
     * @param  PayrollVerified  $event
     * @return void
     */
    public function handle(PayrollVerified $event)
    {
        // Create PDF FILE of the loan application
        $pdf = '';

        // send notif

        $employees = DB::table('viewUserPermissions')->where('permission', 'officer')->get();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $event->loan, 'employee' => $employee];

            $emp = Employee::where('EmpID', $employee->employee_id)
            ->where('DBNAME', $employee->DBNAME)
            ->first();

            if(isset($emp->EmailAdd))
                $this->send($emp, config('preferences.notif_subjects.verified', 'Loan Application Notification'), 'emails.officer', $args, $cc = '');
            
            // Notification
            $notif = new NotificationController();
            $notif->notifyOfficer($event->loan, $employee->employee_id);
        }
    }
}
