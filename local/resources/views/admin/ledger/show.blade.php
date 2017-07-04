@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Employee's Ledger</h1>
		<div class="col-md-7">
			
			<a href="{{ route('ledger.show', $employee->EmpID) }}" class="btn btn-sm btn-default"><i class="fa fa-reload"></i> Refresh</a>
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
		</div>
		<div class="col-md-5">
			<div class="input-group">
				<a class="input-group-addon" title="Application Date"><i class="fa fa-search"></i></a>
				<input type="date" name="from" class="form-control" value="<?php if(isset($_GET['from'])) echo $_GET['from']; ?>">
				<a class="input-group-addon"><i class="fa fa-arrow-right"></i></a>
				<input type="date" name="to" class="form-control" value="<?php if(isset($_GET['to'])) echo $_GET['to']; ?>">
				<span class="input-group-btn">
					<a class="btn btn-success" onclick="find()">Go</a>
				</span>
			</div>

		</div>
		<hr>
		<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: scroll; height: 60vh">
				@include('admin.ledger.ledger')
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	function find() {
		var url = "{{ route('ledger.show', $employee->EmpID) }}";
		url = url + '?bal=<?php if(isset($_GET['bal'])) echo $_GET['bal']; ?>' + "&format=html";
		url = url + "&from=" + $('input[name=from').val();
		url = url + "&to=" + $('input[name=to]').val();

		window.location.href = url;
	}

	if(tour.ended()){
		var ledgerTourShow = new Tour({
			name: 'Ledger_Tour_show',
			steps: Ledger_steps_show,
		});

		ledgerTourShow.init();
		ledgerTourShow.start();
	}
</script>
@endsection