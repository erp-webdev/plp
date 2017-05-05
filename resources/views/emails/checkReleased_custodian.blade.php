@extends('emails.layout')
@section('content')
	<?php $loan = $args['loan'];  
		$utils = $args['utils'];
	
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.check_released_cust') }}</h2>
	</div>
	<div class="content">
	Hi, Mr./Ms. {{ $utils->getFName($employee->FullName) }}!
	<br>
	<br>
	Ah check has been released to {{ $loan->FullName }} with control # {{ $loan->ctrl_no }}.
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