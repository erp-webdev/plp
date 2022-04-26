<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use Log as Logger;
use Mail;
use eFund\Log;
use eFund\Employee;
use eFund\Preference;
use eFund\Http\Requests;
use eFund\Utilities\Utils;
use eFund\Http\Controllers\Controller;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailController extends Controller 
{
    public function send(Employee $emp, $subject, $body, $args, $cc = '')
    {
        try {
            $EnableEmail = Preference::name('email_notifs');

            if($EnableEmail->value != 1)
                return;

            if(empty($emp->EmailAdd))
                return;

            // $emp = Employee::where('EmpID', $toEmpID)->first();
            // if(!empty($emp)){
            //     if(empty($emp->EmailAdd))
            //         return;
            // }else{
            //     return;
            // }

            $to = $emp->EmailAdd;
            $from = config('preferences.email_from');
            $utils = new Utils();

            $mail = Mail::send($body, ['employee' => $emp, 'args' => $args, 'utils' => $utils], function($message) use ($to, $subject, $from, $cc){
                
                if(env('APP_ENV') == 'local'){
                    $message->bcc('kayag.global@megaworldcorp.com');
                    $message->to('kayag.global@megaworldcorp.com');
                    // $message->to($to);
                    $message->from($from);
                    $message->subject($subject . '--testing');
                    
                    // if(!empty(trim($cc)))
                        // $message->cc($cc);  
                }else{
                    $message->bcc('kayag.global@megaworldcorp.com');
                    $message->to($to);
                    $message->from($from);
                    $message->subject($subject);
                    
                    if(!empty(trim($cc)))
                        $message->cc($cc);  
                }

            });

            $log = new Log();
            $log->writeOnly('Info', 'email', ['email' => $to, 'subject' => $subject, 'response' => $mail]);
        } catch (Exception $e) {
            dd($e->message());
        }
    }

    public function sendToMany($emps, $subject, $body, $args, $cc = '')
    {
        try {
            $EnableEmail = Preference::name('email_notifs');

            if($EnableEmail->value != 1)
                return;

            $from = config('preferences.email_from');
            $utils = new Utils();

            $mail = Mail::send($body, ['employee' => $emps[0], 'args' => $args, 'utils' => $utils], function($message) use ($to, $subject, $from, $cc){
                
                if(env('APP_ENV') == 'local'){
                    $message->bcc('kayag.global@megaworldcorp.com');
                    $message->to('kayag.global@megaworldcorp.com');
                    // $message->to($to);
                    $message->from($from);
                    $message->subject($subject . '--testing');
                    
                    // if(!empty(trim($cc)))
                        // $message->cc($cc);  
                }else{
                    $message->bcc('kayag.global@megaworldcorp.com');
                    $message->to($to);
                    $message->from($from);
                    $message->subject($subject);
                    
                    if(!empty(trim($cc)))
                        $message->cc($cc);  
                }

            });

            $log = new Log();
            $log->writeOnly('Info', 'email', ['email' => $to, 'subject' => $subject, 'response' => $mail]);
        } catch (Exception $e) {
            dd($e->message());
        }
    }
}
