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
	                    <div class="modal-body" id="deductionBody">
	                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                        <div class="form-group col-xs-12 col-sm-4 col-md-4">
	                                <span class="col-xs-12 col-sm-3 col-md-3">Date</span>
	                                <div class="col-xs-12 col-sm-9 col-md-9">
	                                      <input name="deductionDate" class="datepicker form-control" ng-model="deductionDate" max="<?php //echo date('Y-m-d'); ?>" onchange="loadBatchDeduction('{{ route('loan.deduction.list') }}', this)" placeholder="YYYY-MM-DD" required>
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
                            <button id="applyDeduction" type="submit" name="save" class="btn btn-sm btn-success" disabled><i class="fa fa-save" onsubmit="startLoading()"></i> Apply Deductions</button>
                        </div>
               		</form>
      			</div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->
		  <div class="modal fade" tabindex="-1" role="dialog" id="email">
		    <div class="modal-dialog modal-lg" role="document">
		      	<div class="modal-contents" style="background-color: #fff">
	                <div class="modal-header">
	                    <div class="modal-title"><h4> Loan Email Notification</h4></div>
	                    <p>Send list of loan applications for verification of payroll.</p>
	                </div>
	                <form action="{{ route('loan.email.notif') }}" method="post">
	                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	                	<div class="modal-body" id="emailModalBody">
		                	
		                </div>
		                <div class="clearfix"></div>
	                    <div class="modal-footer">
	                        <button type="submit" name="send" class="btn btn-sm btn-success" onsubmit="startLoading()"><i class="fa fa-send"></i> Send Email</button>
	                    </div>
	                </form>
	               
      			</div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->
		  <div class="modal fade" tabindex="-1" role="dialog" id="officer">
		    <div class="modal-dialog modal-lg" role="document">
		      	<div class="modal-contents" style="background-color: #fff">
	                <div class="modal-header">
	                    <div class="modal-title"><h4> For Officer's Approval</h4></div>
	                    <p>Send list of loan applications for Officer's Approval</p>
	                </div>
	                <form action="{{ route('loan.officer.notif') }}" method="post">
	                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	                	<div class="modal-body" id="emailOfficerModalBody">
		                	
		                </div>
		                <div class="clearfix"></div>
	                    <div class="modal-footer">
	                        <button type="submit" name="send" class="btn btn-sm btn-success" onsubmit="startLoading()"><i class="fa fa-send"></i> Send Email</button>
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
			<a class="btn btn-sm btn-info" data-toggle="modal" data-target="#email" ng-click="loadPayrollList('{{ route('loan.email.list') }}')"><i class="fa fa-envelope"></i> Payroll Verifications</a>
			<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#officer" ng-click="loadOfficerList('{{ route('loan.officer.list') }}')"><i class="fa fa-envelope"></i> For Officer's Approval</a>
			<a class="btn btn-sm btn-primary pull-right" style="margin-right: 10px" href="{{ route('upload.show') }}"><i class="fa fa-upload"></i> Import</a>
			@endpermission
			<hr>
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
                @elseif ($message = Session::get('info'))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert alert-info">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                @elseif ($message = Session::get('warning'))
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="alert alert-warning">
                            <p>{{ $message }}</p>
                        </div>
                    </div>
                @elseif(count($errors)>0)
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="alert alert-danger col-xs-12 col-sm-5 col-md-5">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
					<div class="input-group col-xs-12 col-sm-3 col-md-3">
						<select name="status" id="status" class="form-control">
							<option value="all" {{ isset($_GET['status']) ? $_GET['status'] === 'all' ? 'selected' : '' : '' }}>Select all</option>
							@foreach($utils->stats as $index => $stat)
								@if(in_array($index, [0, 1, 2]))
									@continue;
								@endif
							<option value="{{ $index }}" {{ isset($_GET['status']) ? $_GET['status'] == $index ? 'selected' : '' : '' }}>{{ $stat }}</option>
							@endforeach
						</select>
						<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
				 	</div>
				 	<div class="input-group col-xs-12 col-sm-3 col-md-3 pull-right">
						<input type="search" id="search" class="form-control input-sm"  placeholder="Ctrl No, Employee, or Date Applied" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
						<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
				 	</div>
		        </div>
	        <div class="clearfix"></div>
				<table id="dataTable"  class="table table-striped table-hover table-condensed">
					<thead>
						<th value="ctrl_no">Control No</th>
						<th value="type">Type</th>
						<th value="company">Company</th>
						<th value="FullName">Employee</th>
						<th value="created_at">Date Applied</th>
						<th value="surety">Surety</th>
						<th value="loan_amount" style="text-align: right">Loan Amount (Php)</th>
						<!-- <th value="ctrl_no" style="text-align: right">Interest Amount (Php)</th> -->
						<th value="total" style="text-align: right">Total (Php)</th>
						<th value="terms_month">Terms (mos)</th>
						<!-- <th value="ctrl_no" style="text-align: right">Deductions (Php)</th> -->
						<th value="paid_amount" style="text-align: right">Amount Paid (Php)</th>
						<th value="balance" style="text-align: right">Balance (Php)</th>
						<th value="status">Status</th>
						<th value="id">Action</th>
					</thead>
					<tbody>
						@foreach($loans as $loan)
							<tr>
								<td>{{ $loan->ctrl_no }}</td>
								<td>{{ $loan->special ? 'SPECIAL' : 'REGULAR' }}</td>
								<td>{{ $loan->COMPANY }}</td>
								<td>{{ utf8_encode($loan->FullName) }}</td>
								<td>{{ $loan->created_at }}</td>
								<td>{{ $loan->guarantor_FullName }}</td>
								<td style="text-align: right">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
								<!-- <td style="text-align: right">{{ number_format($loan->int_amount, 2, '.', ',') }}</td> -->
								<td style="text-align: right">{{ number_format($loan->total, 2, '.', ',') }}</td>
								<td>{{ $loan->terms_month }}</td>
								<!-- <td style="text-align: right">{ { number_format($loan->deductions, 2, '.', ',') }}</td> -->
								<td style="text-align: right">{{ number_format($loan->paid_amount, 2, '.', ',') }}</td>
								<td style="text-align: right">{{ number_format(round($loan->balance,2), 2, '.', ',') }}</td>
								<td>{!! $utils->formatStatus($loan->status) !!}</td>
								<td>
									<a data-toggle="modal" data-target="#loan" ng-click="loadLoan({{ $loan->id }})" class="btn btn-sm btn-info" title="View Loan Application" data-toggle="tooltip"><i class="fa fa-eye"></i></a>
									@if(in_array($loan->status, [$utils->getStatusIndex('inc'), $utils->getStatusIndex('paid')]))
									<a href="{{ route('ledger.show', $loan->EmpID) }}" class="btn btn-sm btn-default" title="Ledger" data-toggle="tooltip"><i class="fa fa-calculator"></i></a>
									@endif
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
	var $by = "<?php if(isset($_GET['by'])) echo $_GET['by']; else echo 'desc'; ?>";
	var $sort = "<?php if(isset($_GET['sort'])) echo $_GET['sort']; else echo 'created_at'; ?>";


	function find() {
		var $show = $('#show').val();
		var $search = $('#search').val();
		var $status = $('#status').val();
		var $searchUrl = "{{ route('admin.loan') }}" + "?show=" + $show + "&search=" + $search + "&sort=" + $sort + "&by=" + $by + '&status=' + $status;
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
			steps: Transaction_index,
		});

		loansTour.init();
		loansTour.start();
	}


	function selectAll(event) {
		$('td input:checkbox').each(function() {
			$(this).prop('checked', event.checked);
			updateARAmount()
		});
	}


$('#dataTable thead').on('click', 'th', function () {
  $sort = $(this).attr('value');
  if($by == 'desc')
  	$by = 'asc';
  else
  	$by = 'desc';

  find();

});

var row = '<tr>' +
		'<td style="width: 25px"><div class="col-md-1"><span class="close ddclose">&times;</span></div><div class="col-md-10"><input type="date" name="date[]" class="form-control" required></div></td>'+
		'<td style="width: 25px"	>'+
		'	<input  class="form-control input-sm" type="text" name="arno[]" value="" required>'+
		'</td>'+
		'<td style="width: 25px"><input class="form-control input-sm" type="number" step="any" name="amount1[]" value="" required></td>'+
		'<td style="width: 25px"><input class="form-control input-sm" type="number" step="any" value="" disabled ></td>'+
	'</tr>';

$(document).on('click', '#new_deduction', function(event) {
	
	$('#dd').append(row);

});

$(document).on('click', '.ddclose', function(event){
	$(this).closest('tr').remove();
});
</script> 
@endsection
