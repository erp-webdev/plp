<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

use DB;
use eFund\Preference;
use eFund\Events\GuarantorApproved;
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
     * @param  GuarantorApproved  $event
     * @return void
     */
    public function handle(GuarantorApproved $event)
    {
        $EnableEmail = Preference::name('payroll_notif');

        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $event->loan, 'employee' => $employee];


            if($EnableEmail->value == 1){
                $emp = Employee::where('EmpID', $employee->employee_id)
                    ->where('DBNAME', $employee->DBNAME)
                    ->first();

                if(isset($emp->EmailAdd))
                    $this->send($emp, config('preferences.notif_subjects.created', 'Loan Application Notification'), 'emails.payroll_verify', $args, $cc = '');
            }
            
            $notif = new NotificationController();
            $notif->notifyPayroll($event->loan, $employee->employee_id);
        }

    }
}
