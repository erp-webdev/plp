	@extends('emails.layout')
@section('content')
	<?php 
		$loans = [$args['loan']];
		$loans[0]->start_of_deductions = date_format($loans[0]->start_of_deductions, 'm/d/y');
		$utils = $args['utils'];
		$employee = $args['employee'];
	  ?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.payroll') }}</h2>
	</div>
	<div class="content">
		Hi, Mr./Ms. {{ $utils->getFName($employee->FullName) }}!
		<br>
		<br>
		@include('admin.reports.payrollNotif')
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