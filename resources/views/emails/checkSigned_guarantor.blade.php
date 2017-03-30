@extends('emails.layout')
@section('content')
	<?php $loan = $args['loan'];  
		$utils = $args['utils'];
	
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.check_signed') }}</h2>
	</div>
	<div class="content">
	Hi, Mr./Ms. {{ $utils->getFName($loan->guarantor_FullName) }}!
	<br>
	<br>
	We are please to inform you that a check is ready for claiming by the eFund applicant of whom you have aggreed to be a co-borrower.
	<br>
	<br>

	Thank you.
	<br>
	<br>
	<br>

	Regards, 
	<br>
	eFund Admin
	</div>
@endsection