@extends('admin.layouts.app')
@section('content')
  <div class="modal fade" tabindex="-1" role="dialog" id="loan">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

	<div class="row" ng-app="ApprovalApp" ng-controller="ApprovalCtrl">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>Loans</h1>
			<a class="btn btn-sm btn-default" href="{{ route('admin.loan') }}"><i class="fa fa-refresh"></i> Refresh</a>
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
				<table class="table table-striped table-hover table-condensed">
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
				{{ $loans->links() }}
			</div>
		</div>
	</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/ApprovalCtrl.js') }}"></script>
<script type="text/javascript">
	var $showUrl = "{{ route('loan.show', 0) }}";
</script> 
@endsection
