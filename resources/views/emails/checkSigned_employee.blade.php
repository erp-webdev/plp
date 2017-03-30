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
		We are pleased to inform you that you may now claim your check at the Treasury Department at {{ date('j F Y', strtotime($loan->check_released)) }}. 
		<br>
		<br>
		Below is a schedule of your deductions per payroll cutoff: 
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