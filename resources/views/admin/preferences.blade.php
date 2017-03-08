@extends('admin.layouts.app')
@section('content')
	<div class="col-md-12">
		<h1>Preferences</h1>
		<p>Manage System configurations, settings, and preferences.</p>
	</div>
	<div class="col-md-8">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<!-- System Settings -->
			<!-- <div class="panel panel-default"> 
	    		<div class="panel-heading" role="tab" role="button" data-toggle="collapse" data-parent="#accordion" href="#system" aria-expanded="true">
	      			<h4 class="panel-title">
	        			System Settings
	      			</h4>
	    		</div>
	    		<div id="system" class="panel-collapse collapse" role="tabpanel">
		      		<div class="panel-body">
			        	
		      		</div>
	    		</div>
			</div>-->

			<div class="panel panel-default">
	    		<div class="panel-heading" role="tab" id="panel1_" role="button" data-toggle="collapse" data-parent="#accordion" href="#Email" aria-expanded="true">
	      			<h4 class="panel-title">
	        			Email Settings
	      			</h4>
	    		</div>
	    		<div id="Email" class="panel-collapse collapse" role="tabpanel">
			        <form action="{{ route('preferences.update') }}" method="post" class="form-horizontal">
		      			<div class="panel-body">
    	  	 		 		<input type="hidden" name="_token" value="{{ csrf_token() }}">
    	  	 		 		<h3>General Email</h3>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="from" class="col-sm-2 control-label">HR Inquiry</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="email" name="email_hr" class="form-control" placeholder="hr@megaworldcorp.com" value="{{ $config['email_hr_inquiry'] }}"> 
    	  	 		 				<span class="help-block">HR Email for user inquiries.</span>
    	  	 		 			</div>
    	  	 		 		</div>

    	  	 		 		<div class="form-group">
    	  	 		 			<label for="from" class="col-sm-2 control-label">Mail Sender</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="email" name="email" class="form-control" placeholder="noreply@megaworldcorp.com" value="{{ $config['email_from'] }}"> 
    	  	 		 				<span class="help-block">All system email sender.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<hr>
    	  	 		 		<!-- <h3>Medicard Email</h3>
    	  	 		 		<p>Email Settings that is used during automatic sending of email to Medicard for Employee's deletion.</p>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="medicard_address" class="col-sm-2 control-label">Email Add (TO)</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="email" name="medicard_address" class="form-control" placeholder="" value="{{ $config['medicard_email_address'] }}"> 
    	  	 		 				<span class="help-block">Medicard Email address.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="medicard_subject" class="col-sm-2 control-label">Subject</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="text" name="medicard_subject" class="form-control" placeholder="" value="{{ $config['medicard_email_subject'] }}">
    	  	 		 				<span class="help-block">Medicard Email Subject.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="medicard_cc" class="col-sm-2 control-label">CC</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="text" name="medicard_cc" class="form-control" placeholder="" value="{{ $config['medicard_email_cc'] }}"> 
    	  	 		 				<span class="help-block">Medicard Email CC. Emails must be separated with comma ','.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<hr>
    	  	 		 		<h3>Insurance Email</h3>
    	  	 		 		<p>Email Settings that is used during automatic sending of email to Insurance for Employee's deletion.</p>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="insurance_address" class="col-sm-2 control-label">Email Add (TO)</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="email" name="insurance_address" class="form-control" placeholder="" value="{{ $config['insurance_email_address'] }}">
    	  	 		 				<span class="help-block">Insurance Email address.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="insurance_subject" class="col-sm-2 control-label">Subject</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="text" name="insurance_subject" class="form-control" placeholder="" value="{{ $config['insurance_email_subject'] }}">
    	  	 		 				<span class="help-block">Insurance Email Subject.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="insurance_cc" class="col-sm-2 control-label">CC</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="text" name="insurance_cc" class="form-control" placeholder="" value="{{ $config['insurance_email_cc'] }}">
    	  	 		 				<span class="help-block">Insurance Email CC. Emails must be separated with comma ','.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<hr> -->
    	  	 		 		<h3>Signatory (Approver) Email</h3>
    	  	 		 		<p>Email Settings that is used during automatic sending of email to signatories.</p>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="approver_subject" class="col-sm-2 control-label">Subject</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="text" name="approver_subject" class="form-control" placeholder="" value="{{ $config['insurance_email_subject'] }}">
    	  	 		 				<span class="help-block">Signatory Email Subject.</span>
    	  	 		 			</div>
    	  	 		 		</div>
    	  	 		 		<div class="form-group">
    	  	 		 			<label for="approver_cc" class="col-sm-2 control-label">CC</label>
    	  	 		 			<div class="col-sm-10">
    	  	 		 				<input type="text" name="approver_cc" class="form-control" placeholder="" value="{{ $config['insurance_email_cc'] }}">
    	  	 		 				<span class="help-block">Signatory Email CC. Emails must be separated with comma ','.</span>
    	  	 		 			</div>
    	  	 		 		</div>
                            <hr>
		      			</div>
		      			<div class="panel-footer">
		      				<button type="submit" name="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
		      			</div>
			        </form>
	    		</div>
			</div>
		</div>
	</div>
@endsection