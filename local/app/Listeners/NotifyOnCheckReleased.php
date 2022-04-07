<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;
use eFund\Employee;

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

        $employee = Employee::where('EmpID', $loan->EmpID)
        ->where('DBNAME', $loan->DBNAME)
        ->first();

        if(isset($employee->EmailAdd))
            $this->send($employee, config('preferences.notif_subjects.check_released', 'Loan Application Notification'), 'emails.checkReleased_employee', $args, $cc = '');

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

            $emp = Employee::where('EmpID', $employee->employee_id)
                ->where('DBNAME', $employee->DBNAME)
                ->first();
            
            if(isset($emp->EmailAdd))
                $this->send($emp, config('preferences.notif_subjects.payroll', 'Loan Application Notification'), 'emails.payroll', $args, $cc = '');
            
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

            $emp = Employee::where('EmpID', $employee->employee_id)
                ->where('DBNAME', $employee->DBNAME)
                ->first();
            
            if(isset($emp->EmailAdd))
                $this->send($emp, config('preferences.notif_subjects.check_released_cust', 'Loan Application Notification'), 'emails.checkReleased_custodian', $args, $cc = '');
            
        }
    }

    public function notifyGuarantor($loan)
    {
        $pref = Preference::name('guarantor_notif');
        if($pref->value != 1)
            return;

        $utils = new Utils();
        $args = ['loan' => $loan, 'utils' => $utils];

        $employee = Employee::where('EmpID', $loan->guarantor_EmpID)
            ->where('DBNAME', $loan->guarantor_dbname)
            ->first();
        
        if(isset($employee->EmailAdd))
            $this->send($employee, config('preferences.notif_subjects.check_signed', 'Loan Application Notification'), 'emails.checkSigned_guarantor', $args, $cc = '');
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
