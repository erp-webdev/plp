<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

use DB;
use eFund\Events\LoanApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyTreasury extends EmailController
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
     * @param  LoanApproved  $event
     * @return void
     */
    public function handle(LoanApproved $event)
    {
        $employees = DB::table('viewUserPermissions')->where('permission', 'treasurer')->get();

        // Notification
        $notif = new NotificationController();
        $notif->notifyEmployeeOnApproved($event->loan);
        
        foreach ($employees as $employee) {
            if(empty($employee->EmailAdd))
                continue;
            
            $args = ['loan' => $event->loan, 'employee' => $employee];

            $this->send($employee->employee_id, config('preferences.notif_subjects.approved', 'Loan Application Notification'), 'emails.treasury', $args, $cc = '');

            $notif->notifyTreasury($event->loan, $employee->employee_id);
        }
    }
}
