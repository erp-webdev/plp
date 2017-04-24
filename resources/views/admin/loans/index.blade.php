@extends('admin.layouts.app')
@section('content')
  <div class="modal fade" tabindex="-1" role="dialog" id="loan">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

	<div class="row" ng-app="ApprovalApp" ng-controller="ApprovalCtrl">
		  <div class="modal fade" tabindex="-1" role="dialog" id="deductions">
		    <div class="modal-dialog modal-lg" role="document">
		      	<div class="modal-contents" style="background-color: #fff">
	                <div class="modal-header">
	                    <div class="modal-title"><h4>Batch Deductions</h4></div>
	                    <p>Batch deduction enables posting of deductions per AR # and date specified.</p>
	                </div>
	                <form action="{{ route('loan.deduction.batch') }}" method="POST" class="form-horizontal">
	                    <div class="modal-body">
	                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                        <div class="form-group col-xs-12 col-sm-4 col-md-4">
	                                <span class="col-xs-12 col-sm-3 col-md-3">Date</span>
	                                <div class="col-xs-12 col-sm-9 col-md-9">
	                                      <input type="date" name="deductionDate" class="form-control" ng-change="loadBatchDeduction('{{ route('loan.deduction.list') }}')" ng-model="deductionDate" max="<?php //echo date('Y-m-d'); ?>" required>
	                                </div>
	                        </div>
	                        <div class="form-group col-xs-12 col-sm-4 col-md-4">
	                                <span class="col-xs-12 col-sm-3 col-md-3">AR #</span>
	                                <div class="col-xs-12 col-sm-9 col-md-9">
	                                        <input type="text" name="d_arno" class="form-control" required>
	                                </div>
	                        </div>
	                        <div class="form-group col-xs-12 col-sm-4 col-md-4">
	                                <span class="col-xs-12 col-sm-3 col-md-3">Amount</span>
	                                <div class="col-xs-12 col-sm-9 col-md-9">
	                                    <input type="number" id="arAmount" step="any" class="form-control" onchange="updateARAmount()">
	                                </div>
	                        </div>
							<div id="deductionBatch" class="col-xs-12"></div>
							<div class="clearfix"></div>
                        </div>
                        <div class="modal-footer">
                            <button id="applyDeduction" type="submit" name="save" class="btn btn-sm btn-success" disabled><i class="fa fa-save"></i> Apply Deductions</button>
                        </div>
               		</form>
      			</div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>Transactions</h1>
			<a id="refreshBtn" class="btn btn-sm btn-default" href="{{ route('admin.loan') }}"><i class="fa fa-refresh"></i> 
			Refresh</a>
			@permission('custodian')
			<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#deductions" ng-click="loadBatchDeduction('{{ route('loan.deduction.list') }}')"> Batch Deductions</a>
			<a class="btn btn-sm btn-primary pull-right" style="margin-right: 10px" href="{{ route('upload.show') }}"><i class="fa fa-upload"></i> Import</a>
			@endpermission
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
				 	<div class="input-group col-xs-12 col-sm-3 col-md-3 pull-right">
						<input type="search" id="search" class="form-control input-sm"  placeholder="Ctrl No, Employee, or Date Applied" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
						<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
				 	</div>
		        </div>
	        <div class="clearfix"></div>
				<table  class="table table-striped table-hover table-condensed">
					<thead>
						<th>Control No</th>
						<th>Employee</th>
						<th>Date Applied</th>
						<th style="text-align: right">Loan Amount (Php)</th>
						<!-- <th style="text-align: right">Interest Amount (Php)</th> -->
						<th style="text-align: right">Total (Php)</th>
						<th>Terms (mos)</th>
						<!-- <th style="text-align: right">Deductions (Php)</th> -->
						<th style="text-align: right">Amount Paid (Php)</th>
						<th style="text-align: right">Balance (Php)</th>
						<th>Status</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($loans as $loan)
							<tr>
								<td>{{ $loan->ctrl_no }}</td>
								<td>{{ utf8_encode($loan->FullName) }}</td>
								<td>{{ $loan->created_at }}</td>
								<td style="text-align: right">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
								<!-- <td style="text-align: right">{{ number_format($loan->int_amount, 2, '.', ',') }}</td> -->
								<td style="text-align: right">{{ number_format($loan->total, 2, '.', ',') }}</td>
								<td>{{ $loan->terms_month }}</td>
								<!-- <td style="text-align: right">{ { number_format($loan->deductions, 2, '.', ',') }}</td> -->
								<td style="text-align: right">{{ number_format($loan->paid_amount, 2, '.', ',') }}</td>
								<td style="text-align: right">{{ number_format($loan->balance, 2, '.', ',') }}</td>
								<td>{!! $utils->formatStatus($loan->status) !!}</td>
								<td>
									<a data-toggle="modal" data-target="#loan" ng-click="loadLoan({{ $loan->id }})" class="btn btn-sm btn-info" title="View Loan Application" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
									<a href="{{ route('ledger.show', $loan->EmpID) }}" class="btn btn-sm btn-default" title="Ledger" data-toggle="tooltip"><i class="fa fa-calculator"></i></a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $loans->appends(Input::All())->links() }}
			</div>
		</div>
	</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/ApprovalCtrl.js') }}"></script>
<script type="text/javascript">
	var $showUrl = "{{ route('loan.show', 0) }}";
	function find() {
		var $show = $('#show').val();
		var $search = $('#search').val();
		var $searchUrl = "{{ route('admin.loan') }}" + "?show=" + $show + "&search=" + $search;
		window.location.href = $searchUrl;
		updateARAmount();
	}

	function updateTotalAR() {
		var $bal = $('#arAmount').val();

		$('.amount').each(function( index ) {

			var id = $(this).closest('tr').find('#id');
			if(id[0].checked == 1){
				$bal -= $(this).val();
			}
			$bal = Math.round($bal*100)/100;
			$('#arBalance').text($bal);

			if($bal == 0){
				$('#applyDeduction').removeAttr('disabled');	
			}else{
				$('#applyDeduction').attr('disabled', 'disabled');	
			}
		});

	}

	function updateARAmount() {
		$('#arBalance').text($('#arAmount').val());
		updateTotalAR();
	}
	
	var $myEFundPage = true;

	if(tour.ended()){
		var loansTour = new Tour({
			name: 'EFund_Tour_loan',
		});

		loansTour.addStep({
		    element: "table",
		    title: "Transactions Listing",
		    content: "All EFund applications are listed and monitored here. It provides a summary of applications and their status.",
		    backdrop: true,
		    backdropContainer : '#app-layout',
		    placement: 'top',
		  });

	  	loansTour.addStep({
		    element: $('#search').closest('div.input-group'),
		    title: "Search Bar",
		    content: "You can search transactions here by providing Ctrl No, Employee ID, or Date of application.",
		    backdrop: true,
		    backdropContainer : '#app-layout',
		    placement: 'left',
		  });

	  if($('a.btn-info:contains(" Batch Deductions")').length){
	    loansTour.addStep({
	      element: $('a.btn-info:contains(" Batch Deductions")'),
	      title: "Batch Processing of Deductions",
	      content: "You can process deductions of all employees with schedule of deductions on the set date. Click the button!",
	      backdrop: true,
	      backdropContainer : '#app-layout',
	    });

	    // loansTour.addStep({
	    //   element: $('input[name="deductionDate"]').closest('.form-group'),
	    //   title: "Deduction Date",
	    //   content: "Chosee date of deductions. This will retrieve all emloyees with loan deductions on the set date.",
	    //   backdrop: true,
	    //   backdropContainer : '#app-layout',
	    //   onShow: function(){
	    //     $('.modal').attr('style', 'z-index:5000');
	    //     $('.modal-backdrop').attr('style', 'z-index:1000');
	    //   }
	    // });

	    // loansTour.addStep({
	    //   element: $('input[name="d_arno"]').closest('.form-group'),
	    //   title: "AR Number",
	    //   content: "AR number is required to process the deductions.",
	    //   backdrop: true,
	    //   backdropContainer : '#app-layout',
	    // });

	    // loansTour.addStep({
	    //   element: $('input[name="save"]'),
	    //   title: "Applying the Deductions",
	    //   content: "Clicking this button will apply the deductions with the AR # to all the listed employees. Applied deductions are automatically posted in the employee's ledger respectively.",
	    //   backdrop: true,
	    //   backdropContainer : '#app-layout',
	    // });
	  }

	if($('i.fa-upload').closest('a').length){
	  loansTour.addStep({
	    element: $('i.fa-upload').closest('a'),
	    title: "Importing Existing Data",
	    content: "Import existing data to the EFund system. Importing data is critical to the system and must follow proper data formats. Imported data can not be undone.",
	    backdrop: true,
	    backdropContainer : '#app-layout',
	    placement: 'left'
	  });


	}

	loansTour.init();
	loansTour.start();
}





</script> 
@endsection
