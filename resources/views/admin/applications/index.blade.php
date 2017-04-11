@extends('admin.layouts.app')

@section('content')
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>My eFunds</h1>
			<button id="refreshBtn" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</button>
			<a href="{{ route('applications.create') }}" class="btn btn-sm btn-success">Apply Loan</a>
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
			<table class="table table-condensed table-hover table-striped">
				<thead>
					<th>Control #</th>
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
						<td>{{ $loan->created_at }}</td>
						<td>{{ $loan->terms_month }}</td>
						<td style="text-align: right">{{ number_format($loan->loan_amount, 2, '.', ',') }}</td>
						<td style="text-align: right">{{ number_format(($loan->loan_amount * ($loan->interest / 100)), 2, '.', ',') }}</td>
						<td style="text-align: right">{{ number_format($loan->total, 2, '.', ',') }}</td>
						<td style="font-size: 14px">{!! $utils->formatStatus($loan->status) !!}</td>
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
			{{ $loans->links() }}
		</div>
	</div>

@endsection
@section('scripts')
<script type="text/javascript">

var myEfundSteps = [
	{
      element: "#refreshBtn",
      title: "Refresh",
      content: "Click this to refresh the page and reset search filters.",
      backdrop: true,
      backdropContainer : '#app-layout',
      
    },
    {
      element: "table",
      title: "EFund Application Listing",
      content: "All your applications are listed here. You can monitor your application's progress by looking at the status indicated.",
      placement: 'top',
      backdrop: true,
      backdropContainer : '#app-layout',
    },
    {
      element: ".btn-success:contains('Apply Loan')",
      title: "Applying a Loan",
      content: "Click this button to create and submit a new or reavailment loan applications. Try it!",
      reflex: true,
      // next: -1,
      backdrop: true,
      backdropContainer : '#wrapper',
      reflex: true,
    }];


if(tour.ended()){
	var myEF1 = new Tour({
		name: 'EFund_Tour_App1',
		steps: myEfundSteps,
	});


	myEF1.init();
	myEF1.start();
}




</script>
@endsection