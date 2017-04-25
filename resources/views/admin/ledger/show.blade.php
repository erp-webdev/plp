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
		<div class="btn-group">
			  	<a type="button" class="btn btn-sm btn-default" href="{{ route('ledger.print', $employee->EmpID) }}?bal=<?php if(isset($_GET['bal'])) echo $_GET['bal']; ?>&format=html" target="_blank" ><i class="fa fa-print"></i> Print</a>
				<a type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				&nbsp;
				<i class="fa fa-caret-down"></i>
				<span class="sr-only">Toggle Dropdown</span>
				&nbsp;
				</a>
			  	<ul class="dropdown-menu">
			  		<li><a href="{{ route('ledger.print', $employee->EmpID) }}?bal=<?php if(isset($_GET['bal'])) echo $_GET['bal']; ?>&format=pdf" target="_blank" >PDF (.pdf)</a></li>
				    <li><a href="{{ route('ledger.print', $employee->EmpID) }}?bal=<?php if(isset($_GET['bal'])) echo $_GET['bal']; ?>&format=xls" target="_blank" >Excel (.xls)</a></li>
				    <!-- <li><a href="{{ route('ledger.print', $employee->EmpID) }}?bal=<?php if(isset($_GET['bal'])) echo $_GET['bal']; ?>&format=csv" target="_blank" >CSV (.csv)</a></li> -->
			  	</ul>
			</div>
		<a href="{{ route('ledger.show', $employee->EmpID) }}" class="btn btn-sm btn-default"><i class="fa fa-reload"></i> Refresh</a>
		<hr>
		<div style="overflow: scroll; height: 60vh">
				@include('admin.ledger.ledger')
			</div>
		</div>
	</div>
</div>
@endsection
