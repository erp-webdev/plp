@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Import</h1>
		<p>To import existing loans, download the excel template <a href="">HERE</a> and fill up required columns before uploading.</p>
		<form action="{{ route('loan.upload') }}" method="post" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="input-group col-xs-12 col-sm-4 col-md-4">
				<input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="fileToUpload" class="form-control input-sm">
				<span class="input-group-btn">
					<button class="btn btn-success btn-sm" type="submit"> Upload</button>
				</span>
			</div>
		</form>
		<hr>

		@if(isset($loans) && (count($loans) > 0))
		<!-- Nav tabs -->
			 <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#loans" aria-controls="loans" role="tab" data-toggle="tab">Loans</a></li>
			    <li role="presentation"><a href="#ledger" aria-controls="ledger" role="tab" data-toggle="tab">Ledger</a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="loans">
			    	<div class="table-responsive">
						<table class="table table-bordered table-hover table-condensed">
							<thead>
								<?php $keys = array_keys($loans[0]) ?>
								<thead>
								@foreach($keys as $key)
									<th>{{ $key }}</th>
								@endforeach
								</thead>
								<tbody>
									@foreach($loans as $loan)
									<tr>
										@foreach($loan as $value)
										<td>{{ $value }}</td>
										@endforeach
									</tr>
									@endforeach
								</tbody>
							</thead>
						</table>
					</div>
			    </div>
			    <div role="tabpanel" class="tab-pane" id="ledger">
			    	<div class="table-responsive">
			    		<table class="table table-bordered table-hover table-condensed">
			    			<thead>
			    				<?php $keys = array_keys($ledger[0]); ?>
			    				@foreach($keys as $key)
			    					<th>{{ $keys }}</th>
			    				@endforeach
			    			</thead>
			    			<tbody>
			    				@foreach($ledgers as $ledger)
			    				<tr>
			    					@foreach($ledger as $value)
			    					<td>$value</td>
			    					@endforeach
			    				</tr>
			    				@endforeach
			    			</tbody>
			    		</table>
			    	</div>
			    </div>
			</div>
		
		@endif
	</div>
</div>
@endsection