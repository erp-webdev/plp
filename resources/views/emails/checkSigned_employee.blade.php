@extends('emails.layout')
@section('content')
	<?php 
		$loan = $args['loan']; 
		$utils = $args['utils'];
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.check_signed') }}</h2>
	</div>
	<div class="content">
		Hi, Mr./Ms. {{ $utils->getFName($loan->FullName) }}!
		<br>
		<br>
		We are pleased to inform you that you may now claim your check at the Treasury Department at {{ date('j F Y', strtotime($loan->check_released)) }}. 
		<br>
		<br>
		Your schedule of deductions per payroll cutoff will be sent after your check is released. 
		<br>
		<br>

	Thank you.
		<br>
		<br>

	Regards, 
		<br>

	eFund Admin
	</div>
@endsection