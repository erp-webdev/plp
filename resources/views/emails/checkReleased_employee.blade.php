@extends('emails.layout')
@section('content')
	<?php 
		$loan = $args['loan']; 
		$deductions = $args['deductions']; 
		$utils = $args['utils'];
	?>
	<div class="title">
		<h2>{{ config('preferences.notif_subjects.check_signed') }}</h2>
	</div>
	<div class="content">
		Hi, Mr./Ms. {{ $utils->getFName($loan->FullName) }}!
		<br>
		<br>
		Your EFund check has been released at {{ date('j F Y', strtotime($loan->released)) }}. 
		<br>
		<br>
		Below is your schedule of deductions per payroll.
		<br><br>
		<table>
			<thead>
				<th width="250px">Date</th>
				<th>Deduction</th>
			</thead>
			<tbody>
				@foreach($deductions as $deduction)
					<tr>
						<td>{{ date('j F Y', strtotime($deduction->date)) }}</td>
						<td>{{ number_format($loan->deductions, 2, '.', ',') }}</td>
					</tr>
				@endforeach
					<tr>
						<td><strong>Total</strong></td>
						<td><strong>{{ number_format($loan->total, 2, '.', ',') }}</strong></td>
					</tr>
			</tbody>
		</table>
		
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