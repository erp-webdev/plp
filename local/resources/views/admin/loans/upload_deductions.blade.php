@extends('admin.layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Upload Deductions</h1>
		<p>To import existing loans, download the excel template <a href="{{ url('/DeductionTemplate.xlsx') }}">HERE</a> and fill up required columns before uploading.</p>
		<form action="{{ route('loan.deduction.upload') }}" method="post" enctype="multipart/form-data">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" >
			<div class="input-group col-xs-12 col-sm-4 col-md-4">
				<input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="fileToUpload" class="form-control input-sm" value="">
				<span class="input-group-btn">
					<button class="btn btn-success btn-sm" type="submit"> Upload</button>
				</span>
			</div>
		</form>
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
        @if(isset($success))
        	<div class="col-xs-12 col-sm-12 col-md-12">
                <div class="alert alert-success">
                    <p>{{ $success }}</p>
                </div>
            </div>
        @endif
        @if(isset($errorss))
        	<div class="col-xs-12 col-sm-12 col-md-12">
                <div class="alert alert-danger">
                    <ul>
                    @foreach($errorss as $err)
                    	<li>{{ $err->error }}</li>
                    @endforeach
                    </ul>
                </div>
            </div>
        @endif

		@if(isset($deductions) && count($deductions) > 0 )
            @if(!$valid)
            <div class="bg-danger">
                <strong>Upload Failed! Please check list of errors</strong>
            </div>
            @else
            <div class="bg-success">
                <strong>Upload successful</strong>
            </div>
            @endif
		<div class="table-responsive">
			<table class="table table-condensed table-bordered table-hover">
				<thead>
					<th style="width:200px">Errors</th>
					@foreach($deductions[0]->data as $key => $value)
					<th>{{ $key }}</th>
					@endforeach
				</thead>
				<tbody>
					@foreach($deductions as $loan)
					<tr>
						<td style="width:200px">
							@if($valid)
								Uploaded
							@else
                                <? echo (!$loan->errors->noActiveLoan) ? 'No active loan' : ''; echo implode(', ', $loan->errors->required); ?> 
								<? echo ($loan->errors->required) ? 'required fields ' : '--'; echo implode(', ', $loan->errors->required); ?> 
                                <br>
							@endif
                            
						</td>
						@foreach($loan->data as $key=>$value)
						<td class="{{ in_array($key, $loan->errors->required) ? 'bg-danger' : '' }}">{{ $value }}</td>
						@endforeach
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endif
		@if(isset($deductions) && (count($deductions) > 0) && false)
		<!-- Nav tabs -->
			 <ul class="nav nav-tabs" role="tablist">
			    <li role="presentation" class="active"><a href="#loans" aria-controls="loans" role="tab" data-toggle="tab">Deductions</a></li>
			    <!-- <li role="presentation"><a href="#ledger" aria-controls="ledger" role="tab" data-toggle="tab">Ledger</a></li> -->
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
			    <div role="tabpanel" class="tab-pane active" id="loans">
			    	<div class="table-responsive">
						<table id="dataTable" class="table table-bordered table-hover table-condensed">
							<thead>
								<thead>
								@foreach($deductions[0] as $key => $value)
									<th>{{ $key }}
								@endforeach
								</thead>
								<tbody>
									@foreach($deductions as $loan)
									<tr>
										@foreach($loan as $key => $value)
										<td <?php if($key!='loc' && empty($value)) echo 'style="background-color: #F90909"'; ?>>{{ $value }}</td>
										@endforeach
									</tr>
									@endforeach
								</tbody>
							</thead>
						</table>
					</div>
			    </div>
			</div>
		
		@endif
	</div>
</div>
@endsection