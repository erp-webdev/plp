<?php

namespace eFund\Listeners;

use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

use Event;
use eFund\Events\GuarantorApproved;
use eFund\Events\EndorsementApproved;
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
     * @param  EndorsementApproved  $event
     * @return void
     */
    public function handle(EndorsementApproved $event)
    {
        $EmpID = $event->loan->guarantor_EmpID;

        if(empty($EmpID)){
            Event::fire(new GuarantorApproved($event->loan));
        }

        $body = 'emails.guarantor';
        
        $notif = new NotificationController();
        $notif->notifyGuarantor($event->loan);

        $args = ['loan' => $event->loan];
        $this->send($EmpID, config('preferences.notif_subjects.created', 'Loan Application Notification'), $body, $args, $cc = '');
    }
}
