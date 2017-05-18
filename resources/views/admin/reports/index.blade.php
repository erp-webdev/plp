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

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<h1>Reports</h1>
		
		<hr>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<ul class="list-group">
				<li class="list-group-item" onclick="$type = 'payroll'; showReport()"><i class="fa fa-book"></i> Payroll Notification</li>
				<li class="list-group-item" onclick="$type = 'summary'; showReport()"><i class="fa fa-book"></i> Loan Summary</li>
				<li class="list-group-item" onclick="$type = 'monthly'; showReport()"><i class="fa fa-book"></i> Monthly Summary</li>
				<li class="list-group-item" onclick="$type = 'deduction'; showReport()"><i class="fa fa-book"></i> With Outstanding and No Deduction</li>
				<li class="list-group-item" onclick="$type = 'resigned'; showReport()"><i class="fa fa-book"></i> Resigned Employees and With Outstanding Balance</li>
				</ul>
			<div>
				<strong>Filter</strong>
				<div id="filter">
					<div class="" style="overflow: scroll; height: 40vh">
						<div class="form-group">
							Application Date <input class="form-control input-sm datepicker" id="fromDate">
							To    <input class="form-control  input-sm datepicker" id="toDate">
						</div>
						<div class="form-group">
							Employee ID
							<input type="search" id="EmpID" class="form-control  input-sm" placeholder="YYYY-MM-XXXX">
						</div>
						<div class="form-group">
							Status
							<select id="status" class="form-control  input-sm">
								<option value="0">All</option>
								<option value="1">Paid</option>
								<option value="2">Active Account</option>
								<option value="3">For Approval</option>
								<option value="4">Denied</option>
							</select>
						</div>
						<!-- <div class="form-group">
							Others
							<select id="others" class="form-control  input-sm">
								<option value="0">Name</option>
								<option value="1">Guarantor</option>
								<option value="2">Check Released</option>
							</select>
						</div> -->
						<div class="form-group">
							Sort By
							<select id="sort" class="form-control  input-sm">
								<option value="FullName">Employee Name</option>
								<option value="ctrl_no">Control #</option>
								<option value="EmpID">Employee ID</option>
								<option value="created_at">Date of Application</option>
								<option value="loan_amount">Loan Amount</option>
							</select>
						</div>
					</div>
					<a onclick="showReport()" class="btn btn-sm btn-block btn-success"><i class="fa fa-filter"></i> Filter</a>
				</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-10 col-md-10">
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-bottom: 5px">
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
				<a class="btn btn-default btn-sm" href="#reportViewModal" data-toggle="modal" data-target="#reporViewModal" onclick="showFullScreen()"><i class="fa fa-eye"></i> View FullScreen</a>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" id="reportView" style="border-left: 1px solid #ccc; height: 70vh; overflow: scroll;"></div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
	var showUrl = "{{ route('report.show', '') }}";
	var	printUrl = "{{ route('report.print', '') }}";
	var $type = '';
	var $format = 'pdf';

	function showReport() {
		if($type == ''){
			$('#reportView').html('<div class="alert alert-danger">Please select report to be generated.</div>');
			return;
		}

		startLoading();

		 $.ajax({
	        type: "GET",
	        url: showUrl + "/" + $type + 
	        		"?dateFrom=" + $('#fromDate').val() +
	        		"&dateTo=" + $('#toDate').val() +
	        		"&EmpID=" + $('#EmpID').val() +
	        		"&sort=" + $('#sort').val() +
	        		"&status=" + $('#status').val(),
	        data: {'_token=': "{{ csrf_token() }}" },
	        success: function(data){
	            $('#reportView').html(data);
				stopLoading();
	          },
	        error:function(data){
	            $('#reportView').html('<span class="text-danger">Something went wrong! Please try again!</span>');
				stopLoading();
	          },
	    });
	}

	function print() {
		if($type == ''){
			$('#reportView').html('<div class="alert alert-danger">Please select report to be generated.</div>');
			return;
		}

		var url = printUrl + "/" + $type + 
	        		"?dateFrom=" + $('#fromDate').val() +
	        		"&dateTo=" + $('#toDate').val() +
	        		"&EmpID=" + $('#EmpID').val() +
	        		"&status=" + $('#status').val() +
	        		"&format=" + format +
	        		'&_token=' + "{{ csrf_token() }}";

	      window.open(url, '_blank');
	}

	function showFullScreen() {
		var report = $('#reportView').html();
		$('.reportViewFS').html(report);
	}

	if(tour.ended()){
		var reportTourIndex = new Tour({
			name: 'Report_Tour_index',
			steps: Report_steps_index,
		});

		reportTourIndex.init();
		reportTourIndex.start();
	}
</script>
@endsection