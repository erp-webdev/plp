@extends('admin.layouts.app')

@section('content')

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>My Loans</h1>
			<a class="btn btn-sm btn-default" href="{{ route('applications.index') }}"><i class="fa fa-refresh"></i> Refresh</a>
			<a href="{{ route('applications.create') }}" class="btn btn-sm btn-primary">New Loan</a>
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
						<input type="search" id="search" class="form-control input-sm"  placeholder="Ctrl No or Date Applied" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
						<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
				 	</div>
		        </div>
	        	<div class="clearfix"></div>
	        	<table class="table table-condensed table-hover table-striped">
					<thead>
						<th>Control #</th>
						<th>Company</th>
						<th>Date</th>
						<th>Terms</th>
						<th style="text-align: right">Loan Amount (Php)</th>
						<th style="text-align: right">Interest Amount (Php)</th>
						<th style="text-align: right">Total (Php)</th>
						<th>Status</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($loans as $loan)
						<tr>
							<td>{{ $loan->ctrl_no }}</td>
							<td>{{ $loan->COMPANY }}</td>
							<td>{{ $loan->created_at }}</td>
							<td>{{ $loan->terms_month }}</td>
							<td style="text-align: right">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
							<td style="text-align: right">{{ number_format(($loan->loan_amount * ($loan->interest / 100)), 2, '.', ',') }}</td>
							<td style="text-align: right">{{ number_format($loan->total, 2, '.', ',') }}</td>
							<td style="font-size: 14px">
							@if($loan->status == 5 && empty($loan->check_released))
								<label class="label label-info">Approved</label>
							@else
								{!! $utils->formatStatus($loan->status) !!}
							@endif
							</td>
							<td>
								<div class="btn-group">
									<a class="btn btn-sm btn-info" title="View Application" data-toggle="tooltip" href="{{ route('applications.show', $loan->id) }}"><i class="fa fa-eye"></i></a>
									@if($loan->status == 0)
									<a class="btn btn-sm btn-danger" href="{{ route('applications.destroy', $loan->id) }}" title="Delete Application" data-toggle="tooltip" onclick="startLoading()"><i class="fa fa-trash"></i></a>
									@endif
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>				
				</table>
            </div>
			
			{{ $loans->links() }}
		</div>
	</div>

@endsection
@section('scripts')
<script type="text/javascript">

if(tour.ended()){
	var myEF1 = new Tour({
		name: 'EFund_Tour_App1',
		steps: MyEFund_index,
	});

	myEF1.init();
	myEF1.start();
}

function find() {
	var $show = $('#show').val();
	var $search = $('#search').val();
	var $searchUrl = "{{ route('applications.index') }}" + "?show=" + $show + "&search=" + $search;
	window.location.href = $searchUrl;
}

</script>
@endsection