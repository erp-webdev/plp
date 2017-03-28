<?php

namespace eFund\Listeners;

use eFund\Events\LoanPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPaidLoanNotif
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
        //
    }
}
