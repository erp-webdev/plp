@extends('admin.layouts.app')
@section('content')
<div class="modal fade" tabindex="-1" role="dialog" id="reporViewModal" >
    <div class="modal-dialog" role="document" style="width: 100%">
      <div class="modal-content">
      	<div class="modal-header">
      		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      	</div>
        <div class="modal-body">
			<div class="reportViewFS">sa</div>
		</div>
		<div class="modal-footer">
			<div class="btn-group">
			  	<button type="button" class="btn btn-sm btn-default" onclick="format = 'html'; print()"><i class="fa fa-print"></i> Print</button>
				<button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				&nbsp;
				<i class="fa fa-caret-down"></i>
				<span class="sr-only">Toggle Dropdown</span>
				&nbsp;
				</button>
			  	<ul class="dropdown-menu">
			  		<li><a onclick="format = 'pdf'; print()">PDF (.pdf)</a></li>
				    <li><a onclick="format = 'xlsx'; print()">Excel (.xlsx)</a></li>
				    <!-- <li><a onclick="format = 'csv'; print()">CSV (.csv)</a></li> -->
			  	</ul>
			</div>
			<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-label="Close">Close</button>
		</div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="filterModal" >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      	<div class="modal-header">
      		<span class="modal-title">Filter</span>
      		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      	</div>
        <div class="modal-body">

			<form class="filterElements">
				<div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Type
					<select name="special" id="special" class="form-control">
						<option value="0" >Regular</option>
						<option value="1">Special</option>
					</select>
				</div>
				<div class="form-group col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Control #
					<input type="text" name="control" class="form-control  input-sm" placeholder="YYYY-MM-XXXX">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Employee ID
					<input type="text" name="EmpID" class="form-control  input-sm" placeholder="YYYY-MM-XXXX">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Employee Name
					<input type="text" name="empName" class="form-control  input-sm" placeholder="Juan or Dela Cruz">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Surety / Guarantor
					<input type="text" name="surety" class="form-control  input-sm" placeholder="Juan or Dela Cruz">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Check Release
					<input name="checkRelease" class="form-control  input-sm datepicker-range" placeholder="mm/dd/yyyy">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Start of Deductions
					<input name="startDeduction" class="form-control  input-sm datepicker-range" placeholder="mm/dd/yyyy">
				</div>	
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					End of Deductions
					<input name="endDeduction" class="form-control  input-sm datepicker" placeholder="mm/dd/yyyy">
				</div>	
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Application Date <input class="form-control input-sm datepicker-range" name="created_at" placeholder="mm/dd/yyyy">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Payroll Period <input class="form-control input-sm datepicker-single" name="payroll" placeholder="mm/dd/yyyy">
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Status
					<select name="status" class="form-control  input-sm">
						<option value="0">All</option>
						<option value="1">Paid</option>
						<option value="2">Active Account</option>
						<option value="3">For Approval</option>
						<option value="4">Denied</option>
					</select>
				</div>
				<div class="form-group  col-xs-12 col-sm-4 col-md-4 col-lg-4">
					Sort By
					<select name="sort" class="form-control  input-sm">
						<option value="FullName">Employee Name</option>
						<option value="ctrl_no">Control #</option>
						<option value="EmpID">Employee ID</option>
						<option value="created_at">Date of Application</option>
						<option value="loan_amount">Loan Amount</option>
					</select>
				</div>
				<div class="clearfix"></div>
				<a onclick="showReport(this, 'view')" class="btn btn-sm btn-block btn-success" data-dismiss="modal" aria-label="Close"><i class="fa fa-filter"></i> Filter</a>
				<a class="btn btn-sm btn-block btn-default clear"><i class="fa fa-eraser"></i> Clear</a>
				<div class="clearfix"></div>
			</form>
		</div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div class="input-group ">
				<div class="input-group-btn">
				    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-book"></i> Report <span class="caret"></span></button>
				    <ul class="dropdown-menu">
				        <li><a onclick="$type = 'payroll'; showReport(this, 'view')">Payroll</a></li>
				        <li><a onclick="$type = 'summary'; showReport(this, 'view')">Loan Summary</a></li>
				        <li><a onclick="$type = 'monthly'; showReport(this, 'view')">Monthly Summary</a></li>
				        <li><a onclick="$type = 'deduction'; showReport(this, 'view')">Cutoff Without Deduction </a></li>
				        <li><a onclick="$type = 'resigned'; showReport(this, 'view')">Resigned Employees with Balance </a></li>
				        <li><a onclick="$type = 'fullypaid'; showReport(this, 'view')">Last Amortization Schedule </a></li>
				     </ul>
				</div><!-- /btn-group -->
				<input type="text" class="form-control" id="reportTypeDisp" readonly>
			</div><!-- /input-group -->
		</div>
		
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<button class="btn btn-default" data-toggle="modal" data-target="#filterModal" id="filterBtn" disabled><i class="fa fa-filter"></i> Filter</button>
			<a class="btn btn-default" href="#reportViewModal" data-toggle="modal" data-target="#reporViewModal" onclick="showFullScreen()" title="View Full screen">&nbsp;<i class="fa fa-eye"></i>&nbsp;</a>
			<div class="btn-group">
				<button type="button" class="btn btn-default" onclick="showReport(this, 'html')"><i class="fa fa-print"></i> Print</button>
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					&nbsp;
					<i class="fa fa-caret-down"></i>
					<span class="sr-only">Toggle Dropdown</span>
					&nbsp;
				</button>
				<ul class="dropdown-menu">
				  	<li><a onclick="showReport(this, 'pdf')">PDF (.pdf)</a></li>
					<li><a onclick="showReport(this, 'xlsx')">Excel (.xlsx)</a></li>
				</ul>
			</div>
		</div>
	</div>
		<br>
		<hr>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="col-xs-12 col-sm-12 col-md-12" id="reportView"> </div>
		</div> 
	</div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
	var showUrl = "{{ route('report.show', '') }}";
	var	printUrl = "{{ route('report.print', '') }}";
	var _token = "{{ csrf_token() }}";
	var $type = '';
	var $format = 'pdf';
</script>

<script type="text/javascript" src="{{ url('/assets/js/report.js') }}"></script>
@endsection
