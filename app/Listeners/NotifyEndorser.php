<?php

namespace eFund\Listeners;

use eFund\Http\Controllers\admin\EmailController;

use eFund\Events\LoanCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyEndorser extends EmailController
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
        $args = ['loan' => $event->loan];
        $this->send($event->loan->endorser_EmpID, config('preferences.notif_subjects.created', 'Loan Application Notification'), 'emails.endorser', $args, $cc = '');
    }
}
