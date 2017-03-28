@extends('admin.layouts.app')
@section('content')
<div class="modal fade" tabindex="-1" role="dialog" id="loan">
    <div class="modal-dialog" role="document">
      	<div class="modal-content">
        	
  		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div ng-app="ApprovalApp" ng-controller="ApprovalCtrl">
	@include('admin.loan')
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>Endorsements</h1>
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
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="table-responsive">
						<table class="table table-hover table-striped table-condensed">
							<thead>
								<th>Reference #</th>
								<th>Control #</th>
								<th>Employee</th>
								<th style="text-align: right">Loan Amount (Php)</th>
								<th style="text-align: right">Total (Php)</th>
								<th>Status</th>
								<th>Signed At</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php $ctr = 0; ?>

								@foreach($endorsements as $item)
									<tr>
										<td>{{ $item->refno }}</td>
										<td>{{ $item->ctrl_no }}</td>
										<td>{{ utf8_encode($item->FullName) }} <br>
										</td>
										<td style="text-align: right">{{ number_format($item->loan_amount, 2, '.', ',') }}</td>
										<td style="text-align: right">{{ number_format($item->total, 2, '.', ',') }}</td>
										<th>{!! $utils->formatApprovalStatus($item->endorser_status, $item->status, 2) !!}</th>
										<td>{{ $item->signed_at }}</td>
										<td>
										@if($item->status > 1)
										<a data-toggle="modal" data-target="#loan" ng-click="loadLoan({{ $item->id }})" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
										@endif
										</td>
									</tr>
									<?php $ctr++; ?>
								@endforeach
							</tbody>
						</table>
						{{ $endorsements->links() }}
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/ApprovalCtrl.js') }}"></script>
<script type="text/javascript">
	var $showUrl = "{{ route('endorsements.show', 0) }}";
</script> 
@endsection