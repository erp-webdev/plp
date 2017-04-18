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
		'check_signed' => 'Loan Check is now ready!',
		'check_signed_cust' => "Applicant's Check is now ready!",
		'payroll' => 'New Loan Deduction Schedule!', 
		'approved' => 'A Loan Application has been approved!',
		'created'	=> 'A Loan Application has been created!',
		'verified'	=> 'A Loan Application has been verified',
		'paid'		=> 'eFund Loan Paid!'
	],

	// eFund System
	'app' => 'Megaworld eFund',
	'version' => '1.0',
];

 ?>
