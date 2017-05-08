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
						<div class="col-md-8"><span ng-bind="date | date: 'Y'"></span></div>
					</div>
					<div class="form-group">
						<span class="col-md-4">Local / Direct Line</span>
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
						<span class="help-block">Min: {{ $terms->min_amount }} - Max: {{ $terms->max_amount }}</span>
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
							<input name="surety"  type="text" class="form-control input-sm" placeholder="YYYY-MM-XXXX" pattern="[0-9]{4}-[0-9]{2}-[a-zA-Z0-9]{4}" ng-model="surety" ng-change="getSurety()" id="surety_input">
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
					<button id="verify" name="verify" class="btn btn-primary btn-block" ><i class="fa fa-save"></i> Verify</button>
				</div>
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success btn-block" <?php if(!isset($loan)) echo 'disabled'; ?> onclick="startLoading()"><i class="fa fa-send"></i> Submit Now</button>
				</div>
			</div>
			@endif
		@else
			<div class="row" style="border-top: 1px solid #ccc; padding-top: 10px">
				<div class="col-md-4">
					<button id="verify" name="verify" class="btn btn-primary btn-block" onclick=""><i class="fa fa-save"></i> Verify</button>
				</div>
				<div class="col-md-4">
					<button id="submit" name="submit" class="btn btn-success btn-block" <?php if(!isset($loan)) echo 'disabled'; ?> onclick=""><i class="fa fa-send"></i> Submit Now</button>
				</div>
			</div>
		@endif	
	</form>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/loanApplication.js') }}"></script>
<script type="text/javascript">
	@if(isset($loan))
		// Saved
		var $id = {{ $loan->id }};
		var $type = {{ $loan->type }};
		var $date = '{{ $loan->created_at }}';
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
		var $head = "";
		var $head_refno = "";
		var $surety = "";
		var $surety_refno = "";
		var $loan_max = {{ $terms->max_amount }};
		var $loan_min = {{ $terms->min_amount }};
	@endif

	
</script>
<script type="text/javascript">

	var myEfundSteps = [
    {
      element: $("span:contains('Type of Application')").closest('.col-md-6'),
      title: "Type of Application and Previous Balance",
      content: "By default, type of application is already determined by the system, so you don't have to choose here. If this is your first time to apply, click New else Reavailment. <br><br> Your previous balance is displayed here. This must always be 0.00 to proceed to application.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
      prev: -1,
    },
    {
      element: $("input[name='loc']").closest('.form-group'),
      title: "Local / Direct Line #",
      content: "Provide your local number or direct line.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='term_mos']").closest('.form-group'),
      title: "Terms",
      content: "Select number of months to pay your loan. Your first loan application of the year can be set to up 12 months while second availment can only be paid until December of the same year.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='loan_amount']").closest('.form-group'),
      title: "Loan Amount",
      content: "Enter loan amount. Your loan amount range varies base on your position as indicated below the input box. Loan amount above minimum requires you to provide your guarantor as well.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='interest']").closest('.form-group'),
      title: "Interest",
      content: "Interest is the loan interest percentage set by EFund Administrator.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("span:contains('Total')").closest('.form-group'),
      title: "Total",
      content: "Total is the total amount to be deducted on your account.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("span:contains('# of payments to be made*')").closest('.form-group'),
      title: "Number of payments",
      content: "Twice the terms you set is the number of payments to be made. Payment is twice a month or every payroll cut-off.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("span:contains('Every payroll deductions*')").closest('.form-group'),
      title: "Deductions",
      content: "Automatic deductions to be made from your salary every cut-off until your loan is fully paid.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("h4:contains('Employee Information ')"),
      title: "Employee Information",
      content: "Click the arrow down to expand your employment information.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'bottom',
    },
    {
      element: $("input[name='head']").closest('div.col-md-4'),
      title: "Endorser",
      content: "Enter Employee ID of your Immediate Head or Department head who will be your endorser.",
      backdrop: true,
      backdropContainer : '#app-layout',
      placement: 'top',
      onNext: function(){
		if($('#surety').attr('style') == 'display: none'){
			myEF2.addStep(
			  {
			    element: $("input[name='loan_amount']").closest('.form-group'),
			    title: "Activating Guarantor",
			    content: "Try providing a loan amount above minimum to activate the guarantor.",
			    backdrop: true,
			    backdropContainer : '#app-layout',
			    placement: 'bottom',
			    reflex: true,
			    onNext: function(){
			    	if($("input[name='loan_amount']").val() <= $("input[name='loan_amount']").attr('min')){
			    		myEF2.prev();
			    	}
			    }
			  });
		}
      }
    },
];

	

if(tour.ended()){
	var myEF2 = new Tour({
		name: 'EFund_Tour_App2',
		steps: myEfundSteps,
		orphan: true,
		onEnd: function(){
			window.location.reload();
		}
	});

	

    myEF2.addSteps([
      {
        element: $("#surety_input").closest('div#surety'),
        title: "Guarantor",
        content: "Enter employee ID of your guarantor. This is required if you have a loan amount above minimum.",
        backdrop: true,
        backdropContainer : '#app-layout',
        placement: 'bottom',
        reflex: true,
        orphan: false,
        onShow: function(){
        	$("input[name='loan_amount']").val($("input[name='loan_amount']").attr('min') + 500);
        }
      },
      {
        element: $("#verify"),
        title: "Verifying your Application",
        content: "Click this button to verify and validate your applications. This will inform you if you can submit the form or check data for corrections.",
        backdrop: true,
        backdropContainer : '#app-layout',
        placement: 'top',
        onShow: function(){
          $('form').attr('action', '');
          $('#verify').removeAttr('disabled');
        }
      },
      {
        element: $("#submit"),
        title: "Submitting your Application",
        content: "Click this button to submit your applications. You cannot modify your application form once submitted. Your application will be received first by your endorser.  ",
        backdrop: true,
        backdropContainer : '#app-layout',
        placement: 'top',
        onShow: function(){
          $('form').attr('action', '');
          $('#submit').removeAttr('disabled');
        }
      },
      {
        title: "Loan Application",
        content: "Once you submitted an application, it will be provided with a control number. You shall received an email if a check is ready for your claiming. A schedule of payroll deductions is also included in the email.",
        backdrop: true,
        backdropContainer : '#app-layout',
      },
      {
        title: "Payroll Deductions",
        content: "EFund Custodian shall update your ledger every payroll cut-off until you are fully paid.",
        backdrop: true,
        backdropContainer : '#app-layout',
      },
      {
        title: "Fully Paid",
        content: "You will be notified once your application has been fully paid. You may now apply for a new application.",
        backdrop: true,
        backdropContainer : '#app-layout',
      }
    ]);

	myEF2.init();
	myEF2.start();
}

</script>
@endsection