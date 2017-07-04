<?php 

return [

	/*=================================================
	=            Loan Web App Messages            =
	=================================================*/

	/*----------  Loan Application  ----------*/
	'application' => [
		'success'			=> 'Loan application has been submitted successfully! Please wait until all approvers approved your request.  Thank You!',
		'failed' 			=> 'Loan application failed to submit. Please try again!',
		'delete'			=> 'Loan application was successfully deleted.',
		'approved'			=> 'Loan application has been approved successfully.',
		'approved2' 		=> 'Loan application has been approved already.',
		'denied'			=> 'Loan application has been denied successfully.',
		'denied2'			=> 'Loan application has been denied already.',
		'cheque'			=> 'Check saved successfully.',
		'released'  		=> 'Check released successfully',
		'verified' 			=> 'Loan application has been verified successfully! You may now submit this application.', 
		'deduction' 		=> 'Deductions successfully updated', 
		'payroll_verified'	=> 'Loan amount verification has been verified successfully!',
		'payroll_denied'	=> 'Loan amount verification has been denied successfully!',
		'balance'			=> 'Loan application has a remaining balance.',
		'calculated'		=> 'Loan has been re-calculated successfully!',
		'paid'				=> 'Loan has been paid successfully!',
	],


	/*----------  Loan Application Validation  ----------*/
	'validation' => [
		'balance' 	=> 'Your account has a standing balance from previous loan applications!',
		'type' 		=> 'Invalid type of application.',
		'availment'	=> 'Maximum loan application reached!',
		'terms'		=> 'Terms exceeded allowable number of months.',
		'regular' 	=> 'Not a regular employee or deactivated record!',
		'minimum'	=> 'Loan amount below minimum loanable!',
		'maximum'	=> 'Loan amount above maximum loanable!',
		'active'  	=> 'Employee is not found or has been deactivated!',
		'guarantor' => 'Invalid Guarantor. Guarantor may not be active or a regular employee or may have a standing balance.',
		'guaranteed_amount' => 'Specified guarantor has insufficient amount that can be guaranteed on your application.',
		'endorser' 	=> 'Invalid Endorser. Endorser may not be active or a regular employee.',
		'arExists' 	=> 'Failed to update deductions. AR # already exists from our records.',
	],

	/*=====  End of Loan Application Messages  ======*/


];