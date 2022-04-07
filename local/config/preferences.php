<?php
return [
	// Logs
	'log_enabled' 							=> true,
	// drivers: database, laravel, both
	'log_driver' 							=> 'database',

	// Email
	'email_from' 							=> 'plp-noreply@alias.megaworldcorp.com',
	'emial_cc'								=> '',

	// Employee Notification
	'notif_subjects' => [
		'check_signed' 			=> 'PLP Check Is Now Ready!',
		'check_released' 		=> 'PLP Check Has Been Released!',
		'check_signed_cust' 	=> "Applicant's Check Is Now Ready!",
		'check_released_cust' 	=> "Applicant's Check Has Been Released!",
		'payroll' 				=> 'New PLP Deduction Schedule!', 
		'approved' 				=> 'PLP Application Approved!',
		'created'				=> 'New PLP Application!',
		'verified'				=> 'PLP Application Verified!',
		'paid'					=> 'PLP Paid!',
		'paid_payroll'			=> 'PLP Fully Paid Notification',
		'denied'				=> 'PLP Application Denied!'
	],

	// eFund System
	'app' => 'Personal Loan Program',
	'version' => '1.0',
];

 ?>
