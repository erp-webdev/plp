@extends('emails.layout')
@section('content')
	<?php 
	$employee = $args['employee'];
	$loansHtml = $args['loansHtml'];
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.created') }}</h2>
	</div>
	<div class="content">
	Hi, Mr./Ms. {{ $utils->getFName($employee->FullName) }}!
	<br>
	<br>
	The following list is for loan verification.

	{!! $loansHtml !!}

	Click <a href="{{ route('payroll.index') }}">here</a> to view and approve the loan application(s).
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