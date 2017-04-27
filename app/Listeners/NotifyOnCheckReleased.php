<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

use DB;
use eFund\Deduction;
use eFund\Preference;
use eFund\Utilities\Utils;
use eFund\Events\CheckReleased;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOnCheckReleased extends EmailController
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

     public function notifyEmployee($loan)
    {
        $pref = Preference::name('emp_notif');
        if($pref->value != 1)
            return;

        $deductions = Deduction::where('eFundData_id', $loan->id)->
                    orderBy('date')->get();

        $utils = new Utils();
        $args = ['loan' => $loan, 'deductions' => $deductions, 'utils' => $utils];

        $this->send($loan->EmpID, config('preferences.notif_subjects.check_released', 'Loan Application Notification'), 'emails.checkReleased_employee', $args, $cc = '');

        // Notification
        $notif = new NotificationController();
        $notif->notifyOnCheckReleased($loan);
    }

    public function notifyPayroll($loan)
    {
        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();
        $utils = new Utils();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $loan, 'employee' => $employee, 'utils' => $utils];
            $this->send($employee->employee_id, config('preferences.notif_subjects.payroll', 'Loan Application Notification'), 'emails.payroll', $args, $cc = '');
            
        }
    }

    public function notifyCustodian($loan)
    {
        $pref = Preference::name('cust_notif');
        if($pref->value != 1)
            return;
        
        $employees = DB::table('viewUserPermissions')->where('permission', 'custodian')->get();
        $utils = new Utils();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
                
            $args = ['loan' => $loan, 'employee' => $employee, 'utils' => $utils];

            $this->send($employee->employee_id, config('preferences.notif_subjects.check_released_cust', 'Loan Application Notification'), 'emails.checkReleased_custodian', $args, $cc = '');
            
        }
    }

    public function notifyGuarantor($loan)
    {
        $pref = Preference::name('guarantor_notif');
        if($pref->value != 1)
            return;

        $utils = new Utils();
        $args = ['loan' => $loan, 'utils' => $utils];

        $this->send($loan->guarantor_EmpID, config('preferences.notif_subjects.check_signed', 'Loan Application Notification'), 'emails.checkSigned_guarantor', $args, $cc = '');
    }

    /**
     * Handle the event.
     *
     * @param  CheckReleased  $event
     * @return void
     */
    public function handle(CheckReleased $event)
    {
        $this->notifyEmployee($event->loan);
        $this->notifyPayroll($event->loan);
        $this->notifyCustodian($event->loan);
        $this->notifyGuarantor($event->loan);
    }
}
