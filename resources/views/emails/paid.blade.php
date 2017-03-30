@extends('emails.layout')
@section('content')
	<?php $loan = $args['loan'];  ?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.paid') }}</h2>
	</div>
	<div class="content">
	Hi, Mr./Ms. {{ $utils->getFName($loan->FullName) }}!
	<br>
	<br>
	Good News! Your eFund Loan with control no.: {{ $loan->ctrl_no }} has been paid completely.
	Click <a href="{{ route('applications.show', $loan->id) }}">here</a> to view your application.
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