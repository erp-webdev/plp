<?php

namespace eFund\Http\Controllers\admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use eFund\Notification;
use eFund\Http\Requests;
use eFund\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function getType($index)
    {
    	$types = [
    		'default',
    		'primary',
    		'info',
    		'success',
    		'warning',
    		'danger'
    	];

    	return $types[$index];
    }

   	public function post($to, $title, $msg, $type = 'default')
   	{
   		$notif = new Notification();
   		$notif->title = $title;
   		$notif->message = $msg;
   		$notif->sender = Auth::user()->employee_id;
   		$notif->receiver = $to;
   		$notif->type = $type;
   		$notif->save();
   	}

   	public function postGroup($to, $title,$msg, $group, $type = 'default')
   	{
   		$employees = DB::table('viewUserPermissions')->where('permission', $group)->get();
   		foreach($employees as $emp){
   			$this->post($emp->employee_id, $title, $msg, $type);
   		}
   	}

   	public function seen($id)
   	{
   		$notif = Notification::find($id);
   		$notif->seen = date('Y-m-d H:i:s');
   		$notif->save();

   	}

   	// Successful application submission
   	public function notifyAppSubmission($loan)
   	{
   		$to = Auth::user()->employee_id;
   		$title = 'Loan application successful';
   		$msg = 'Your loan application has been submitted successfully with control number ' . $loan->ctrl_no;
   		$type = $this->getType(3);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Endorser
   	public function notifyEndorser($loan)
   	{
   		$to = $loan->endorser_EmpID;
   		$title = 'New loan application for endorsement approval';
   		$msg = 'You have a loan application for endorsement approval with control number ' . $loan->ctrl_no;
   		$type = $this->getType(0);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Guarantor
   	public function notifyGuarantor($loan)
   	{
   		$to = $loan->guarantor_EmpID;
   		$title = 'New loan application for approval as guarantor';
   		$msg = 'You have a loan application for approval as guarantor with control number ' . $loan->ctrl_no;
   		$type = $this->getType(0);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Payroll
   	public function notifyPayroll($loan, $payrollEmpID)
   	{
   		$to = $payrollEmpID;
   		$title = 'New loan application for payroll verification';
   		$msg = 'You have a loan application for payroll verification with control number ' . $loan->ctrl_no;
   		$type = $this->getType(0);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Officer
   	public function notifyOfficer($loan, $officerEmpID)
   	{
   		$to = $officerEmpID;
   		$title = 'New loan application for Final Approval';
   		$msg = 'You have a loan application for your approval with control number ' . $loan->ctrl_no;
   		$type = $this->getType(0);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Treasury
   	public function notifyTreasury($loan, $treasuryEmpID)
   	{
   		$to = $treasuryEmpID;
   		$title = 'New approved loan application';
   		$msg = 'A new loan application has been approved. Please prepare check and voucher information with control number ' . $loan->ctrl_no;
   		$type = $this->getType(0);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Employee on Approved Loan
   	public function notifyEmployeeOnApproved($loan)
   	{
   		$to = $loan->EmpID;
   		$title = 'Loan Application Approved!';
   		$msg = 'Your loan application has been approved with control number ' . $loan->ctrl_no;
   		$type = $this->getType(3);

   		$this->post($to, $title, $msg, $type);
   	}

   	// Employee / Guarantor on check ready
   	public function notifyOnCheckReady($loan)
   	{
   		$to = $loan->EmpID;
   		$title = 'Your check is now ready!';
   		$msg = 'Your check is now ready for claiming.';
   		$type = $this->getType(2);

   		$this->post($to, $title, $msg, $type);

   		$to = $loan->guarantor_EmpID; 
   		$title = 'A check is now ready.';
   		$msg = 'A loan applicant whom you are a co-borrower has now a check ready for claiming.';
   		$type = $this->getType(1);

   		$this->post($to, $title, $msg, $type);

      $to = $loan->guarantor_EmpID; 
      $title = 'New payroll deduction schedule.';
      $msg = 'A new payroll deduction schedule for loan application has been posted.';
      $type = $this->getType(1);

      $this->post($to, $title, $msg, $type);

      $employees = DB::table('viewUserPermissions')->where('permission', 'payroll')->get();

        foreach ($employees as $employee) {
          $to = $employee->employee_id; 
          $title = 'A check has been released.';
          $msg = 'A check has been released. A loan application with control # ' . $loan->ctrl_no . ' is ready for deductions.';
          $type = $this->getType(1);

          $this->post($to, $title, $msg, $type);
        }
   	}

    public function notifyAppDenied($loan)
    {
      $to = $loan->EmpID;
      $title = 'Loan application denied!';
      $msg = 'Your loan application with control number ' . $loan->ctrl_no . ' has been denied.';
      $type = $this->getType(5);

      $this->post($to, $title, $msg, $type);
    }

   	// payroll on deduction sched

    public function notifyPaid($loan)
   {
      $to = $loan->EmpID;
      $title = 'Loan paid!';
      $msg = 'Your loan application with control number ' . $loan->ctrl_no . ' has been paid in full.';
      $type = $this->getType(3);

      $this->post($to, $title, $msg, $type);
   }     
    

}
