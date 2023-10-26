<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

use DB;
use Mail;
use eFund\Loan;
use eFund\Employee;
use eFund\Deduction;
use eFund\Preference;
use eFund\Utilities\Utils;
use eFund\Events\CheckSigned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSignedCheckNotif extends EmailController
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
     * Notify Employee, guarantor and custodian
     *
     * @param  CheckSigned  $event
     * @return void
     */
    public function handle(CheckSigned $event)
    {
        $this->notifyEmployee($event->loan);
        // $this->notifyGuarantor($event->loan);
        // $this->notifyCustodian($event->loan);
    }

    public function notifyEmployee($loan)
    {
        $pref = Preference::name('emp_notif');
        if($pref->value != 1)
            return;

        $utils = new Utils();
        $args = ['loan' => $loan, 'utils' => $utils];

        $employee = Employee::where('EmpID', $loan->EmpID)
            ->where('DBNAME', $loan->DBNAME)
            ->first(); 

        if(isset($employee->EmailAdd))
            $this->send($employee, config('preferences.notif_subjects.check_signed', 'Loan Application Notification'), 'emails.checkSigned_employee', $args, $cc = '');

        // Notification
        $notif = new NotificationController();
        $notif->notifyOnCheckReady($loan);
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
                $this->send($emp, config('preferences.notif_subjects.check_signed_cust', 'Loan Application Notification'), 'emails.checkSigned_custodian', $args, $cc = '');
            
        }
    }
   
}
