@extends('admin.layouts.app')
@section('content')
<div ng-app="loanApp" ng-controller="LoanCtrl">
	<div class="row" >
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>	Loan Application 
					<span style="font-size: 14px; font-weight: normal">
						@if(isset($loan))
							{!! $utils->formatStatus($loan->status) !!}
						@endif
					</span>
			</h1>
			@if(isset($loan))
				<h4><?php if(isset($loan)) if($loan->ctrl_no != '0000') echo 'Ctrl No: '. $loan->ctrl_no; ?></h4>
			@endif
			<hr>
			@if(count($errors)>0)
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="alert alert-danger col-xs-12 col-sm-5 col-md-5">
                        <strong>Attention!</strong><br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>    
            @endif
			@if ($message = Session::get('success'))
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            @elseif ($message = Session::get('error'))
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            @endif
		</div>
	</div>
	<input type="hidden" id="getEmployeeURL" value="{{ url('/getEmployee') }}">
	<form class="form form-horizontal" action="{{ route('applications.store') }}" method="post" ">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="number" id="id" name="id" ng-model="id" style="display: none">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-8">
				<div class="col-md-6">
					<span>Type of Application</span> <br>
					<label style="font-weight: normal">
						<input type="radio" name="type" value="0" ng-checked="type == 0" readonly> New
					</label>
					<label style="font-weight: normal">
						<input type="radio" name="type" value="1" ng-checked="type == 1" readonly> Reavailment @if($records_this_year > 0)
							[{{ $records_this_year }}]
						@endif
					</label> <br>
					<span>Previous Balance</span>
					<u>{{ number_format($balance, 2, '.', ',') }}</u>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<span class="col-md-4">Date</span	>
						<div class="col-md-8"><span ng-bind="date | date: 'shortDate'"></span></div>
					</div>
					<div class="form-group">
						<span class="col-md-4">Local / Direct Line*</span>
						<div class="col-md-8"><input type="text" name="loc" ng-model="loc" class="form-control input-sm" placeholder="loc. 322" required></div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				<div class="alert alert-warning">
					Note: Please verify your application first before submitting. 
				</div>
			</div>
		</div>
		<div class="row" style="border-top: 1px solid #ccc">
			<div class="col-md-4" >
				<h4>Loan Information</h4>
				<div class="form-group" >
					<span class="col-md-4">Terms*</span>
					<div class="col-md-8">
						<div class="input-group">
							<input type="number" name="term_mos" min="1" max="{{ $months }}" class="form-control input-sm" ng-model="mos" required>
							<span class="input-group-addon">Month(s)</span>
						</div>
					</div>
				</div>
				<div class="form-group" id="loan_amount">
					<span class="col-md-4">Loan Amount</span>
					<div class="col-md-8">
						<div class="input-group">
							<span class="input-group-addon">Php</span>
							<input name="loan_amount" type="number" class="form-control input-sm" name="loan_amount" ng-model="loan" required ng-change="computeTotal()" ng-keyup="computeTotal()" step="500" max="<?php if($overMax == 0) echo $terms->max_amount; ?>">
							<span class="input-group-addon">.00</span>
						</div>
						<span class="help-block">Min: {{ $terms->min_loan_amount }} - Max: {{ $terms->max_loan_amount }}</span>
					</div>
				</div>
				<div class="form-group">
					<span class="col-md-4">Interest </span>
					<div class="col-md-8">
						<div class="input-group">
							<input type="text" class="form-control input-sm" ng-model="interest" name="interest" disabled>
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<span class="col-md-4">Total </span>
					<div class="col-md-8">
						<div class="input-group">
							<span style="font-size: 14px; font-weight: bold" ng-bind="total | currency: 'Php '" ng-init="computeTotal()"></span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<!-- Deductions Table -->
				<h4>Deductions</h4>
				<div class="form-group">
					<span class="col-md-8"># of payments to be made*</span>
					<div class="col-md-4">
						<span ng-bind="paymentCtr"></span>
					</div>
				</div>
				<div class="form-group">
					<span class="col-md-8">Every payroll deductions*</span>
					<div class="col-md-4">
						<span ng-bind="deductions | currency: 'Php '"></span>
					</div>
				</div>
				<div class="alert alert-info">
					*Terms applied are subject to changes by the eFund Custodian, 
					thus the *no. of payments and *payroll deductions may change as well. <br>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4">
				
				<h4 data-toggle="collapse" data-parent="#accordion" href="#my_info">Employee Information <i class="fa fa-caret-down"></i></h4>
				<div id="my_info" class="panel-collapse collapse">
						<h4></h4>
						<div class="form-group" >
							<span class="col-md-4">Employee ID</span>
							<div class="col-md-8">
								<input type="text" class="form-control input-sm" name="EmpID" value="{{ $employee->EmpID }}" required disabled>
							</div>
						</div>
						<div class="form-group">
							<span class="col-md-4">Name</span>
							<div class="col-md-8">
								<input type="text"  class="form-control input-sm" value="{{ $employee->FullName }}" disabled>
							</div>
						</div>
						<div class="form-group">
							<span class="col-md-4">Position</span>
							<div class="col-md-8">
								<input type="text" class="form-control input-sm" value="{{ $employee->PositionDesc }}" disabled>
							</div>
						</div>
						<div class="form-group">
							<span class="col-md-4">Rank</span>
							<div class="col-md-8">
								<input type="text" class="form-control input-sm" value="{{ $employee->RankDesc }}" disabled>
							</div>
						</div>
						<div class="form-group">
							<span class="col-md-4">Date Hired</span>
							<div class="col-md-8">
								<input type="text" class="form-control input-sm" value="{{ date('j F Y', strtotime($employee->HireDate)) }}" disabled>
							</div>
						</div>
						<div class="form-group">
							<span class="col-md-4">Regularization</span>
							<div class="col-md-8">
								<input type="text" class="form-control input-sm" value="{{ date('j F Y', strtotime($employee->PermanencyDate)) }}" disabled>
							</div>
						</div>
						<div class="form-group">
							<span class="col-md-4">Department</span>
							<div class="col-md-8">
								<input type="text" class="form-control input-sm" value="{{ $employee->DeptDesc }}" disabled>
							</div>
						</div>
				</div>
			</div>
		</div>
		<div class="row" style="border-top: 1px solid #ccc">
			<div class="col-md-4">
				<h4>Endorser <small>(Immediate/Department Head)</small></h4>
				<div class="form-group" id="head_">
					<span class="col-md-4">Employee ID</span>
					<div class="col-md-8">
						<input name="head" type="text" class="form-control input-sm" placeholder="YYYY-MM-XXXX" pattern="[0-9]{4}-[0-9]{2}-[a-zA-Z0-9]{4}" ng-model="head" ng-change="getHead()" required>
					</div>
				</div>
				<div class="form-group">
					<span class="col-md-4">Name</span>
					<div class="col-md-8">
						<span id="head_name"></span>
					</div>
				</div>
			</div>
			<div id="surety" style="display: none">
				<div class="col-md-4" style="border-top: 1px solid #ccc;" id="surety">
					<h4>Surety / Co-borrower</h4>
					<div class="form-group" id="surety_">
						<span class="col-md-4">Employee ID</span>
						<div class="col-md-8">
							<input name="surety"  type="text" class="form-control input-sm" placeholder="YYYY-MM-XXXX" pattern="[0-9]{4}-[0-9]{2}-[a-zA-Z0-9]{4}" ng-model="surety"  ng-change="getSurety()" id="surety_input">
						</div>
					</div>
					<div class="form-group">
						<span class="col-md-4">Name</span>
						<div class="col-md-8">
							<span id="surety_name"></span>
						</div>
					</div>
				</div>
			</div>

		</div>
		@if(isset($loan))
			@if($loan->status == $utils->getStatusIndex('saved'))
			<div class="row" style="border-top: 1px solid #ccc; padding-top: 10px">
				<div class="col-md-4">
					<button id="verify" name="verify" class="btn btn-primary btn-block" onsubmit="startLoading()"><i class="fa fa-save"></i> Verify</button>
				</div>
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success btn-block" <?php if(!isset($loan)) echo 'disabled'; ?> onsubmit="startLoading()"><i class="fa fa-send"></i> Submit Now</button>
				</div>
			</div>
			@endif
		@else
			<div class="row" style="border-top: 1px solid #ccc; padding-top: 10px">
				<div class="col-md-4">
					<button id="verify" name="verify" class="btn btn-primary btn-block" onsubmit=""><i class="fa fa-save"></i> Verify</button>
				</div>
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success btn-block" <?php if(!isset($loan)) echo 'disabled'; ?> onsubmit="startLoading()"><i class="fa fa-send"></i> Submit Now</button>
				</div>
			</div>
		@endif	
	</form>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/loanApplication.js') }}"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		getHead();
		getSurety();
	});

	@if(isset($loan))
		// Saved
		var $id = {{ $loan->id }};
		var $type = {{ $loan->type }};
		var $date = '{{ date("j F Y", strtotime($loan->created_at)) }}';
		var $loc = '{{ $loan->local_dir_line }}';
		var $term = {{ $loan->terms_month }};
		var $loan_amount = {{ $loan->loan_amount }};
		var $interest = {{ $loan->interest }};
		var $total = {{ $loan->total }};
		var $deductions = {{ $loan->deductions }};
		var $head = "{{ $loan->endorser_EmpID }}";
		var $head_refno = "{{ $loan->endorser_refno }}";
		var $surety = "{{ $loan->guarantor_EmpID }}";
		var $surety_refno = "{{ $loan->guarantor_refno }}";
		var $loan_max = {{ $terms->max_amount }};
		var $loan_min = {{ $terms->min_amount }};
	@elseif(!empty(old('loan_amount')))
		// New With Errors
		var $id = 0;
		var $type = {{ old('type') }};
		var $date = "{{ date('j F Y') }}";
		var $loc = "{{ old('loc') }}";
		var $term = {{ old('term_mos') }};
		var $loan_amount = {{ old('loan_amount') }};
		var $interest = {{ $interest }};
		var $total = 0;
		var $deductions = 0;
		var $head = "{{ old('head') }}";
		var $head_refno = "";
		var $surety = "{{ old('surety') }}";
		var $surety_refno = "";
		var $loan_max = {{ $terms->max_amount }};
		var $loan_min = {{ $terms->min_amount }};
	@else
		// New
		var $id = 0;
		var $type = <?php if($records == 0) echo 0; else echo 1; ?>;
		var $date = "{{ date('j F Y') }}";
		var $loc = "";
		var $term = 1;
		var $loan_amount = {{ $terms->min_amount }};
		var $interest = {{ $interest }};
		var $total = 0;
		var $deductions = 0;
		var $head = "{{ $endorser }}";
		var $head_refno = "";
		var $surety ="{{ $guarantor }}";
		var $surety_refno = "";
		var $loan_max = {{ $terms->max_amount }};
		var $loan_min = {{ $terms->min_amount }};
	@endif

	
</script>
<script type="text/javascript">

if(tour.ended()){
	var myEF2 = new Tour({
		name: 'EFund_Tour_App2',
		steps: MyEFund_create,
		orphan: true,
		onEnd: function(){
			window.location.reload();
		}
	});

	myEF2.init();
	myEF2.start();
}

</script>
@endsection