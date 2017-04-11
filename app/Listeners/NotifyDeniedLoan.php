<?php

namespace eFund\Listeners;
use eFund\Http\Controllers\admin\NotificationController;


use eFund\Events\LoanDenied;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyDeniedLoan
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
    }
}
