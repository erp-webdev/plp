<?php

namespace eFund\Listeners;

use Mail;
use eFund\Log;
use eFund\Loan;
use eFund\Employee;
use eFund\Deduction;
use eFund\Preference;
use eFund\Utilities\Utils;
use eFund\Events\CheckSigned;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSignedCheckNotif
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
     * Notify Employee, guarantor and payroll
     *
     * @param  CheckSigned  $event
     * @return void
     */
    public function handle(CheckSigned $event)
    {
        $this->notifyEmployee($event->loan);
        $this->notifyGuarantor($event->loan);
        $this->notifyPayroll($event->loan);
    }

    public function notifyEmployee($loan)
    {
        $emp = Employee::where('EmpID', $loan->EmpID)->first();

        if(empty($emp)){
            return;
        }

        $deductions = Deduction::where('eFundData_id', $loan->id)->
                    orderBy('date')->get();

        $to = $emp->EmailAdd;
        $from = config('preferences.email_from');
        $subject = config('preferences.notif_subjects.check_signed');
        $body = 'emails.checkSigned_employee';
        $cc = config('preferences.email_cc');

        $args = ['loan' => $loan, 'deductions' => $deductions];

        $this->send($emp, $to, $from, $subject, $body, $cc, $args);
    }

    public function notifyGuarantor($loan)
    {
        $emp = Employee::where('EmpID', $loan->guarantor_EmpID)->first();

        if(empty($emp)){
            return;
        }

        $to = $emp->EmailAdd;
        $from = config('preferences.email_from');
        $subject = config('preferences.notif_subjects.check_signed');
        $body = 'emails.checkSigned_guarantor';
        $cc = config('preferences.email_cc');

        $args = ['loan' => $loan];

        $this->send($emp, $to, $from, $subject, $body, $cc, $args);
    }

    public function notifyPayroll($loan)
    {
        $EmpID = Preference::name('payroll');
        $emp = Employee::where('EmpID', $EmpID->value)->first();

        if(!empty($emp)){

            $to = $emp->EmailAdd;
            $from = config('preferences.email_from');
            $subject = config('preferences.notif_subjects.payroll');
            $body = 'emails.payroll';
            $cc = config('preferences.email_cc');

            $utils = new Utils();
            $args = ['loan' => $loan, 'utils' => $utils, 'emp' => $emp];

            $this->send($emp, $to, $from, $subject, $body, $cc, $args);
        }
    }

    public function send($emp, $to, $from, $subject, $body, $cc, $args)
    {
        Mail::send($body, ['args' => $args], function($message) use ($to, $subject, $from, $cc){
            $message->to($to);
            $message->from($from);
            $message->subject($subject);
            // $message->cc($cc);
        });

        $log = new Log();
        $log->writeOnly('Info', 'email', ['email' => $to, 'subject' => $subject]);
        
    }
}
