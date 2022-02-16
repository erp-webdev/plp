<?php

namespace eFund\Listeners;

use eFund\Http\Controllers\admin\EmailController;

use eFund\Events\LoanCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use eFund\Http\Controllers\admin\NotificationController;

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
        if($event->loan->special == 1){
            if(!empty($event->loan->company_nurse))
                return;
        }

        $args = ['loan' => $event->loan];
        // Notification
        $notif = new NotificationController();

        $nurses = DB::table('viewUserPermissions')->where('permission', 'nurse')->get();
        foreach($nurses as $nurse){
            $notif->notifyCompanyNurse($event->loan, $nurse->employee_id);
            $this->send($nurse->employee_id, config('preferences.notif_subjects.created', 'Special Loan Application Notification'), 'emails.nurse_validation', $args, $cc = '');
        }

        
    }
}
