<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Mail;
use eFund\Log;
use eFund\Employee;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function send($toEmpID, $subject, $body, $args, $cc = '')
    {
        $emp = Employee::where('EmpID', $toEmpID)->first();
        if(!empty($emp)){
        	if(empty($emp->EmailAdd))
        		return;
        }else{
            return;
        }

    	$to = $emp->EmailAdd;
        $from = config('preferences.email_from');
        $utils = new Utils();

        Mail::send($body, ['employee' => $emp, 'args' => $args, 'utils' => $utils], function($message) use ($to, $subject, $from, $cc){
            $message->to($to);
            $message->from($from);
            $message->subject($subject);
            // $message->cc($cc);
        });

        $log = new Log();
        $log->writeOnly('Info', 'email', ['email' => $to, 'subject' => $subject]);
        
    }
}
