@extends('admin.layouts.app')
@section('content')
  <div class="modal fade" tabindex="-1" role="dialog" id="loan">
    <div class="modal-dialog " role="document">
      <div class="modal-content">
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

   
	<div class="row" ng-app="ApprovalApp" ng-controller="ApprovalCtrl">
		<div class="modal fade" tabindex="-1" role="dialog" id="deductions">
		    <div class="modal-dialog modal-lg" role="document">
		      	<div class="modal-contents" style="background-color: #fff">
	                <div class="modal-header">
	                    <div class="modal-title"><h4>Deduction List</h4></div>
	                </div>
                    <div class="modal-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                <span class="col-xs-12 col-sm-3 col-md-3">Deduction Date</span>
                                <div class="col-xs-12 col-sm-5 col-md-5">
                                      <input name="deductionDate" class="datepicker form-control" ng-model="deductionDate" onchange="loadBatchDeduction('{{ route('loan.deduction.list') }}', this)" placeholder="YYYY-MM-DD" autocomplete="off" required>
                                </div>
                        </div>
                        <hr>
						<div id="deductionBatch"></div>
						<div class="clearfix"></div>
                    </div>
      			</div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>Loans</h1>
			<a href="{{ route('payroll.index') }}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</a>
			<a id="deductionList" class="btn btn-sm btn-default" data-toggle="modal" data-target="#deductions">Deductions List</a>
			
			<hr>
			@if ($message = Session::get('success'))
	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="alert alert-success">
	                	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
	                    <p>{{ $message }}</p>
	                </div>
	            </div>
	        @elseif ($message = Session::get('error'))
	            <div class="col-xs-12 col-sm-12 col-md-12">
	                <div class="alert alert-danger">
	                	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
	                    <p>{{ $message }}</p>
	                </div>
	            </div>
	        @endif
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
					<div class="form-group col-xs-12 col-sm-3 col-md-3">
						<span class="col-xs-12 col-md-3 col-sm-3">
							Company
						</span>
						<?php $comp = 'all'; if(isset($_GET['company'])) $comp = $_GET['company']; ?>
						<div class="col-xs-12 col-md-9 col-sm-9">
							<select class="form-control input-sm" id="comp" onchange="find()">
								<option value="all"  <?php if($comp=='all') echo 'selected'; ?>>All</option>
								@foreach($companies as $company)
									<option value="{{ $company->COMPANY }}" {{ $company->COMPANY == $comp ? 'selected' : '' }}>{{ $company->COMPANY }}</option>
								@endforeach
								</select>
						</div>
					</div>
				 	<div class="input-group col-xs-12 col-sm-3 col-md-3 pull-right">
						<input type="search" id="search" class="form-control input-sm"  placeholder="Control #, FullName, EmpID, Deduction date" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
						<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
				 	</div>
			    </div>
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<th>Company</th>
						<th>EF Control No</th>
						<th>Employee ID</th>
						<th>Employee Name</th>
						<th>Date of Check Release</th>
						<th style="text-align: right">Total Amount (Php)</th>
						<th style="text-align: right">Deduction (Php)</th>
						<th>Start of Deduction</th>
						<th>Status</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($loans as $loan)
							<tr>
								<td>{{ $loan->COMPANY }}</td>
								<td>{{ $loan->ctrl_no }}</td>
								<td>{{ $loan->EmpID }}</td>
								<td>{{ utf8_encode($loan->FullName) }}</td>
								<td>{{ $loan->check_released }}</td>
								<td style="text-align: right">{{ number_format($loan->total, 2, '.', ',') }}</td>
								<td style="text-align: right">{{ number_format($loan->deductions, 2, '.', ',') }}</td>
								<td>{{ $loan->start_of_deductions }}</td>
								<td>
								{!! $utils->formatPayrollStatus($loan->payroll_verified) !!}</td>
								<td>
									<a data-toggle="modal" data-target="#loan" ng-click="loadLoan({{ $loan->id }})" class="btn btn-sm btn-info" title="View Loan Application" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $loans->links() }}
			</div>
		</div>
	</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/ApprovalCtrl.js') }}"></script>
<script type="text/javascript">
	var $showUrl = "{{ route('payroll.show', 0) }}";

	function find() {
		var $show = $('#show').val();
		var $search = $('#search').val();
		var $comp = $('#comp').val();
		var $searchUrl = "{{ route('payroll.index') }}" + "?show=" + $show + "&search=" + $search + "&company=" + $comp;
		window.location.href = $searchUrl;
	}

	if(tour.ended()){
		var payrollTourIndex = new Tour({
			name: 'Payroll_Tour_index',
			steps: Payroll_steps_index,
		});

		payrollTourIndex.init();
		payrollTourIndex.start();
	}
</script> 
@endsection
