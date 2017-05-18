<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\NotificationController;
use eFund\Http\Controllers\admin\EmailController;


use eFund\Events\LoanDenied;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyDeniedLoan extends EmailController
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
     * @param  LoanDenied  $event
     * @return void
     */
    public function handle(LoanDenied $event)
    {
        $notif = new NotificationController();
        $notif->notifyAppDenied($event->loan);


        $args = ['loan' => $event->loan];
        $this->send($event->loan->EmpID, config('preferences.notif_subjects.denied', 'Loan Application Notification'), 'emails.denied', $args, $cc = '');
    }
}
