<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\EmailController;
use eFund\Http\Controllers\admin\NotificationController;

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

        // Notification
        $notif = new NotificationController();
        $notif->notifyPaid($event->loan);

        $this->send($event->loan->EmpID, config('preferences.notif_subjects.paid', 'Loan Application Notification'), 'emails.paid', $args, $cc = '');
            
    }
}
