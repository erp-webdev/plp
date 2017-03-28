@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Employee's Ledger</h1>
		@if(!$showBalance)
			<a href="{{ route('ledger.show', $employee->EmpID) }}?bal=true" class="btn btn-sm btn-default"><i class="fa fa-eye"></i> Show Balance</a>
		@else
			<a href="{{ route('ledger.show', $employee->EmpID) }}?bal=false" class="btn btn-sm btn-default"><i class="fa fa-eye-slash"></i> Hide Balance</a>
		@endif
		<a href="{{ route('ledger.show', $employee->EmpID) }}" class="btn btn-sm btn-default"><i class="fa fa-reload"></i> Refresh</a>
		<hr>
		<table class="table-condensed">
			<tr>
				<td>Employee Name</td>
				<td>{{ $employee->FullName }}</td>
			</tr>
			<tr>
				<td>Employee ID No</td>
				<td>{{ $employee->EmpID }}</td>
			</tr>
			<tr>
				<td>Date Hired</td>
				<td>{{ $employee->HireDate }}</td>
			</tr>
			<tr>
				<td>Position/Rank</td>
				<td>{{ $employee->PositionDesc }} / {{ $employee->RankDesc }}</td>
			</tr>
		</table>
		<hr>
			@include('admin.ledger.ledger')
			{{ $ledgers->appends(Input::all())->links() }}
		</div>
	</div>
</div>
@endsection
