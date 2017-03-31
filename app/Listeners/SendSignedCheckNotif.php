<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;

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
     * Notify Employee, guarantor and payroll
     *
     * @param  CheckSigned  $event
     * @return void
     */
    public function handle(CheckSigned $event)
    {
        $this->notifyEmployee($event->loan);
        $this->notifyGuarantor($event->loan);
        $this->notifyPayroll($event->loan);
    }

    public function notifyEmployee($loan)
    {
        if((Preference::name('emp_notif'))->value != 1)
            return;

        $deductions = Deduction::where('eFundData_id', $loan->id)->
                    orderBy('date')->get();

        $utils = new Utils();
        $args = ['loan' => $loan, 'deductions' => $deductions, 'utils' => $utils];

        $this->send($loan->EmpID, config('preferences.notif_subjects.check_signed', 'Loan Application Notification'), 'emails.checkSigned_employee', $args, $cc = '');
    }

    public function notifyGuarantor($loan)
    {
        if((Preference::name('guarantor_notif'))->value != 1)
            return;

        $utils = new Utils();
        $args = ['loan' => $loan, 'utils' => $utils];

        $this->send($loan->guarantor_EmpID, config('preferences.notif_subjects.check_signed', 'Loan Application Notification'), 'emails.checkSigned_guarantor', $args, $cc = '');
    }

    public function notifyPayroll($loan)
    {
        if((Preference::name('payroll_notif'))->value != 1)
            return;
        
        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();
        $utils = new Utils();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $loan, 'employee' => $employee, 'utils' => $utils];

            $this->send($employee->employee_id, config('preferences.notif_subjects.payroll', 'Loan Application Notification'), 'emails.payroll', $args, $cc = '');
            
        }
    }
   
}
