<?php
return [
	// Logs
	'log_enabled' 							=> true,
	// drivers: database, laravel, both
	'log_driver' 							=> 'database',

	// Email
	'email_from' 							=> 'noreply@megaworldcorp.com',
	'emial_cc'								=> '',

	// Employee Notification
	'notif_subjects' => [
		'check_signed' 			=> 'EFund Check Is Now Ready!',
		'check_released' 		=> 'EFund Check Has Been Released!',
		'check_signed_cust' 	=> "Applicant's Check Is Now Ready!",
		'check_released_cust' 	=> "Applicant's Check Has Been Released!",
		'payroll' 				=> 'New EFund Deduction Schedule!', 
		'approved' 				=> 'EFund Application Approved!',
		'created'				=> 'New EFund Application!',
		'verified'				=> 'EFund Application Verified!',
		'paid'					=> 'EFund Paid!',
		'denied'				=> 'EFund Application Denied!'
	],

	// eFund System
	'app' => 'Megaworld EFund System',
	'version' => '1.0',
];

 ?>
