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
										<th>{!! $utils->formatApprovalStatus($item->endorser_status, $item->status, $utils->getStatusIndex('endorser')) !!}</th>
										<td>{{ $item->signed_at }}</td>
										<td>
										@if($item->status >= $utils->getStatusIndex('endorser'))
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

	function find() {
		var $show = $('#show').val();
		var $search = $('#search').val();
		var $searchUrl = "{{ route('endorsements.index') }}" + "?show=" + $show + "&search=" + $search;
		window.location.href = $searchUrl;
	}
</script> 
@endsection