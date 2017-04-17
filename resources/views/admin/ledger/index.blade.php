@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Employees' Ledger</h1>
		<hr>
		<div class="table-responsive">
			<div class="form-horizontal ">
				<div class="form-group col-xs-12 col-sm-2 col-md-2">
					<span class="col-xs-12 col-md-3 col-sm-3">
						Show
					</span>
					<?php $show = 0; if(isset($_GET['show'])) $show = $_GET['show']; ?>
					<div class="col-xs-12 col-md-9 col-sm-9">
						<select class="form-control input-sm" id="show" onchange="find()">
							<option value="0"  <?php if($show==0) echo 'selected'; ?>>All</option>
							<option value="10" selected  <?php if($show==10) echo 'selected'; ?>>10</option>
							<option value="20"  <?php if($show==20) echo 'selected'; ?>>20</option>
							<option value="50"  <?php if($show==50) echo 'selected'; ?>>50</option>
							<option value="100"  <?php if($show==100) echo 'selected'; ?>>100</option>
						</select>
					</div>
				</div>
			 	<div class="input-group col-xs-12 col-sm-3 col-md-3 pull-right">
					<input type="search" id="search" class="form-control input-sm"  placeholder="Search" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
					<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
			 	</div>
		    </div>
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

		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
	function find() {
		var $show = $('#show').val();
		var $search = $('#search').val();
		var $searchUrl = "{{ route('ledger.index') }}" + "?show=" + $show + "&search=" + $search;
		window.location.href = $searchUrl;
	}
</script>
@endsection