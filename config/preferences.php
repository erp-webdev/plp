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
		'payroll' => 'New Loan Deduction Schedule!', 
		'approved' => 'New Loan Application has been approved!',
	],

	// eFund System
	'app' => 'Megaworld eFund',
	'version' => '1.0',
];

 ?>
