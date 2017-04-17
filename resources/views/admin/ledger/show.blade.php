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
		<a href="{{ route('ledger.print', $employee->EmpID) }}?bal=<?php if(isset($_GET['bal'])) echo $_GET['bal']; ?>" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Print</a>
		<a href="{{ route('ledger.show', $employee->EmpID) }}" class="btn btn-sm btn-default"><i class="fa fa-reload"></i> Refresh</a>
		<hr>
		<div style="overflow: scroll; height: 60vh">
				@include('admin.ledger.ledger')
			</div>
		</div>
	</div>
</div>
@endsection
