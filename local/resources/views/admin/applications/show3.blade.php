@extends('admin.layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>Loan Application</h1>
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
			<div class="table-responsive col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<table class="table table-hover table-striped">
					<tbody>
						<tr>
							<td><label>Control #</label></td>
							<td>{{ $loan->ctrl_no }}</td>
						</tr>
						<tr>
							<td><label>Status</label></td>
							<td>
							@if($loan->status == 5 && empty($loan->check_released))
								<label class="label label-info">Approved</label>
							@else
								{!! $utils->formatStatus($loan->status) !!}
							@endif
							</td>
						</tr>
						<tr>
							<td><label>Date</label></td>
							<td>{{ date('j F Y', strtotime($loan->created_at)) }}</td>
						</tr>
						<tr>
							<td><label>Local / Direct Line</label></td>
							<td>{{ $loan->local_dir_line }}</td>
						</tr>
						
						<tr>
							<td><label>Terms</label></td>
							<td>{{ $loan->terms_month }}</td>
						</tr>
						<tr>
							<td><label>Loan Amount</label></td>
							<td>{{ number_format($loan->loan_amount, 2) }}</td>
						</tr>
						<tr>
							<td><label>Interest</label></td>
							<td>{{ number_format(($loan->interest/100 )* $loan->terms_month * $loan->loan_amount, 2) }}</td>
						</tr>
						<tr>
							<td><label>Total</label></td>
							<td><strong>{{ number_format($loan->total, 2) }}</strong></td>
						</tr>
						<tr>
							<td><label># of Payments</label></td>
							<td>{{ $loan->terms_month * 2 }}</td>
						</tr>
						<tr>
							<td><label>Deductions/payroll</label></td>
							<td>{{ number_format($loan->deductions, 2) }}</td>
						</tr>
						<tr>
							<td><label>Endorser</label></td>
							<td>{{ $loan->endorser_FullName }}</td>
						</tr>
						<tr>
							<td><label>Surety/Coborrower</label></td>
							<td>{{ $loan->guarantor_FullName }}</td>
						</tr>
						@if(in_array($loan->status, [0, 1, 2, 9, 10]))
						<tr>
							<td>Cancel Application</td>
							<td>
								<form action="{{ route('applications.destroy', $loan->id ) }}" method="post">
									{{ csrf_field() }}

									<button type="submit" class="btn btn-danger">Cancel</button>
								</form>
							</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection