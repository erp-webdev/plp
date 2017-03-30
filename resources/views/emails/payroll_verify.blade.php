@extends('emails.layout')
@section('content')
	<?php 
	$loan = $args['loan'];  
	$employee = $args['employee'];
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.created') }}</h2>
	</div>
	<div class="content">
	Hi, Mr./Ms. {{ $utils->getFName($employee->FullName) }}!
	<br>
	<br>
	A new loan application has been created with control no.: {{ $loan->ctrl_no }}.
	Click <a href="{{ route('payroll.index') }}">here</a> to view and approve the loan application.
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