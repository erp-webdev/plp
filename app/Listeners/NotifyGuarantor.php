<?php

namespace eFund\Listeners;

use eFund\Http\Controllers\admin\EmailController;

use eFund\Events\LoanCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyGuarantor extends EmailController
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
        $EmpID = $event->loan->guarantor_EmpID;
        $body = 'emails.guarantor';

        if($event->loan->guarantor_id == NULL){
            $EmpID = $event->loan->endorser_EmpID;
            $body = 'emails.endorser';
        }

        $args = ['loan' => $event->loan];
        $this->send($EmpID, config('preferences.notif_subjects.created', 'Loan Application Notification'), $body, $args, $cc = '');
    }
}
