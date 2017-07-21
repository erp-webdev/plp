<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

use DB;
use eFund\Preference;
use eFund\Events\LoanPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class NotifyPaidEmployee  extends EmailController
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
     * @param  LoanPaid  $event
     * @return void
     */
    public function handle(LoanPaid $event)
    {
        $args = ['loan' => $event->loan];

        // Notify employee on their respective paid account.
        $notif = new NotificationController();
        $notif->notifyPaid($event->loan);

        $this->send($event->loan->EmpID, config('preferences.notif_subjects.paid', 'Loan Application Notification'), 'emails.paid', $args, $cc = '');

        // Notify payroll of the fully paid loan app of employee
        $EnableEmail = Preference::name('payroll_notif');

        $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();

        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $event->loan, 'employee' => $employee];


            if($EnableEmail->value == 1){
                $this->send($employee->employee_id, config('preferences.notif_subjects.paid_payroll', 'Fully Paid EFund Notification'), 'emails.paid_payroll', $args, $cc = '');
            }
        }

            
    }
}
