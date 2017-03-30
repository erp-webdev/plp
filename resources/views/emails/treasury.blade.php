@extends('emails.layout')
@section('content')
	<?php 
		$loan = $args['loan'];  
		$employee = $args['employee'];
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.approved') }}</h2>
	</div>
	<div class="content">
	Hi, Mr./Ms. {{ $utils->getFName($employee->FullName) }}!
	<br>
	<br>
	A new loan application has been created with control no.: {{ $loan->ctrl_no }}.<br>
	Click <a href="{{ route('treasury.index') }}">here</a> to provide check and voucher information.
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