<?php

namespace eFund\Listeners;

use eFund\Http\Controllers\admin\EmailController;
use DB;
use eFund\Events\LoanCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use eFund\Http\Controllers\admin\NotificationController;
use eFund\Employee;


class NotifyCompanyNurse extends EmailController
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
     * @param  LoanCreated  $event
     * @return void
     */
    public function handle(LoanCreated $event)
    {
        if($event->loan->special == 0){
            return;

        if($event->loan->special == 1){
            if(!empty($event->loan->company_nurse))
                return;
        }

        $args = ['loan' => $event->loan];
        // Notification
        $notif = new NotificationController();

        $nurses = DB::table('viewUserPermissions')->where('permission', 'nurse')->get();

        foreach($nurses as $nurse){

            $employee = Employee::where('EmpID', $nurse->employee_id)
                ->where('DBNAME', $nurse->DBNAME)
                ->first();

            if(isset($employee->EmailAdd)){
                $notif->notifyCompanyNurse($event->loan, $nurse->employee_id);
                $this->send($employee, config('preferences.notif_subjects.created', 'Special Loan Application Notification'), 'emails.nurse_validation', $args, $cc = '');
            }

            
        }

        
    }
}
