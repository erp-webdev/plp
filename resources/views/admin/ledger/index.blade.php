@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Employees' Ledger</h1>
		<hr>
		<div class="table-responsive">
			<table class="table table-condensed table-hover table-striped">
				<thead style="text-align: center">
					<th>Employee ID</th>
					<th>Employee Name</th>
					<th>Date Hired</th>
					<th>Position</th>
					<th>Rank</th>
					<th>Action</th>
				</thead>
				<tbody>
					@foreach($employees as $employee)
					<tr>
						<td>{{ $employee->EmpID }}</td>
						<td>{{ $employee->FullName }}</td>
						<td>{{ $employee->HireDate }}</td>
						<td>{{ $employee->PositionDesc }}</td>
						<td>{{ $employee->RankDesc }}</td>
						<td><a href="{{ route('ledger.show', $employee->EmpID) }}" class="btn btn-sm btn-default"><i class="fa fa-eye"></i></a></td>
					</tr>
					@endforeach
				</tbody>
			</table>

			{{ $employees->links() }}
		</div>
	</div>
</div>
@endsection
