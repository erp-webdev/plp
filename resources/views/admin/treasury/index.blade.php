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
			<h1>Treasury</h1>
			<button class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</button>
			<hr>
			<div class="table-responsive">
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<th>Control No</th>
						<th>Employee</th>
						<th>Date Applied</th>
						<th>CV #</th>
						<th>CV Date</th>
						<th>Check #</th>
						<th>Check Date</th>
						<th>Status</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($loans  as $loan)
						<tr>
							<td>{{ $loan->ctrl_no }}</td>
							<td>{{ $loan->FullName }}</td>
							<td>{{ date('j F y', strtotime($loan->created_at)) }}</td>
							<td>{{ $loan->cv_no }}</td>
							<td>
								@if(!empty($loan->cv_date))
									{{ date('j F y', strtotime($loan->cv_date)) }}
								@endif
							</td>
							<td>{{ $loan->check_no }}</td>
							<td>
								@if(!empty($loan->check_released))
									{{ date('j F y', strtotime($loan->check_released)) }}
								@endif
							</td>
							<td>{!! $utils->formatTreasuryStatus($loan->status) !!}</td>
							<td>
								<a data-toggle="modal" data-target="#loan" ng-click="loadLoan({{ $loan->id }})" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
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
	var $showUrl = "{{ route('treasury.show', 0) }}";
</script> 
@endsection
