@extends('admin.layouts.app')
@section('content')
	<form id="clearanceForm" action="{{ route('applications.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
	<div class="col-xs-12 col-sm-12 col-md-12">
	    <div>
	        <h2>Create Clearance</h2>
	        <p></p>
	    </div>
	    <div>
        	<!-- <a class="btn btn-default" href="{{ route('applications.index') }}"> <i class="fa fa-arrow-left"></i> Back</a> -->
			<button id="submit" class="btn btn-success" onclick=""><i class="fa fa-play"></i> Submit Clearance</button>
	    </div>
		<hr/>
	</div>
    <div id="error" class="col-xs-12 col-sm-12 col-md-12">
	 @if($message = Session::get('errors'))
    	<div class="alert alert-danger fade out col-xs-12 col-sm-5 col-md-5">
    		<p><?php print_r($message)  ?></p>
    	</div>
	@endif
    </div>    
	<div id="success" class="col-xs-12 col-sm-12 col-md-12">
	@if ($message = Session::get('success'))
		<div class="alert alert-success fade out" role="alert">
			<p>{{ $message }}</p>
		</div>
	@endif
	</div>
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        <div>
        	 <ul class="nav nav-tabs" role="tablist">
        	  <li id="modeTab" role="presentation" class="active"><a href="#employee_info" aria-controls="employee_info" role="tab" data-toggle="tab">Details</a></li>
			   <li id="modeTab" role="presentation" ><a href="#clearance_info" aria-controls="clearance_info" role="tab" data-toggle="tab">Attachment</a></li>
			</ul>
        </div>
        <div class="tab-content">
		  	<!-- Mode Tab -->
		    <div role="tabpanel" class="tab-pane fade in active" id="employee_info">
				<div id="employee_info" class="col-xs-12 col-sm-12 col-md-8">
					<div class="form-group">
						<label class="control-label col-md-5">ID</label>
						<div class="col-md-7">
							<div class="input-group">
								<input type="text" name="employee_id" id="employee_id" action="{{route('applications.search') }}" onchange="showResult(this);" class="form-control" value="{{ Input::old('employee_id') }}" required>
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">Find <i id="loading"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div id="employee" class="col-md-8 col-md-offset-3">
						
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Resignation Date</label>
						<div class="col-md-7">
							<input type="date" id="resgination_date" name="resignation_date" class="form-control" value="{{ Input::old('resignation_date') }}">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Reason for Clearance</label>
						<div class="col-md-7">
							<div class="dropdown">
								<input name="reason" placeholder="Reason" class="form-control dropdown-toggle" id="reason" data-toggle="dropdown" value="{{ Input::old('reason') }}" required>
								<ul class="dropdown-menu" aria-labelledby="destination">
	          						<li><a onclick="dropdown(this)">Resignation</a></li>
	          						<li><a onclick="dropdown(this)">Transfer</a></li>
	          						<li><a onclick="dropdown(this)">End of Probationary</a></li>
	          						<li><a onclick="dropdown(this)">End of Contract</a></li>
								</ul>
							</div>
							<span class="help-block">Select reason or enter your other reason.</span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Email Address</label>
						<div class="col-md-7">
							<input type="email" id="email" name="email" class="form-control" value="{{ Input::old('email') }}" required>
							<span class="help-block">Please provide other email but not company email.</span>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Contact Number</label>
						<div class="col-md-7">
							<input type="text" id="contact_no" name="contact_no" class="form-control" value="{{ Input::old('contact_no') }}">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Address</label>
						<div class="col-md-7">
							<textarea id="address" name="address" class="form-control" rows="3">{{ Input::old('address') }}</textarea>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
		    </div>
			<div role="tabpanel" class="tab-pane fade in" id="clearance_info">
				<div id="clearance_info" class="col-xs-12 col-sm-12 col-md-4">
					<!-- attachments -->
					<div class="form-group">
						<label>Resignation Letter</label>
						<input type="file" name="fileToUpload" id="fileToUpload" class="form-control" accept="application/pdf" value="{{ Input::old('fileToUpload') }}">
						<span class="help-block">Attachment must be in PDF format. 200kb max file size.</span>
					</div>
					<!-- <button id="submit" type="submit" role="button" onclick="upload();">Submit</button> -->
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</form>	
	<div class="col-xs-12 col-sm-12 col-md-12">
		<hr/>
	</div>
@endsection
@section('scripts')
	<script type="text/javascript">
		var curEmpId = '{{ Auth::user()->employee_id }}';
	</script>
	<script type="text/javascript" src="/assets/js/application.js"></script>
@endsection