<?php

namespace eFund\Events;

use eFund\Loan;
use eFund\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GuarantorApproved extends Event
{
    use SerializesModels;

    public $loan; 

    public function __construct(Loan $loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
