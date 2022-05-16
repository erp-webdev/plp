@extends('admin.layouts.app')
@section('content')
<div class="modal fade" tabindex="-1" role="dialog" id="loan">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

	<div class="row" ng-app="ApprovalApp" ng-controller="ApprovalCtrl">

		<div class="modal fade" tabindex="-1" role="dialog" id="transmittal">
		    <div class="modal-dialog modal-lg" role="document">
		      	<div class="modal-contents" style="background-color: #fff">
	                <div class="modal-header">
	                    <div class="modal-title"><h4> For Check Voucher Transmittal</h4></div>
	                    <p>Send list of loan applications with released checks</p>
	                </div>
	                <form action="{{ route('tresury.email.notif') }}" method="post">
	                	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	                	<div class="modal-body" id="emailTreasuryModalBody">
		                	
		                </div>
		                <div class="clearfix"></div>
	                    <div class="modal-footer">
	                        <button type="submit" name="send" class="btn btn-sm btn-success" onsubmit="startLoading()"><i class="fa fa-send"></i> Send Email</button>
	                    </div>
	                </form>
	               
      			</div><!-- /.modal-content -->
		    </div><!-- /.modal-dialog -->
		  </div><!-- /.modal -->

		<div class="col-xs-12 col-sm-12 col-md-12">
			<h1>Treasury</h1>
			
			<div class="col-xs-12 col-sm-8 col-md-8"> 
				<a class="btn btn-sm btn-default" href="{{ route('treasury.index') }}"><i class="fa fa-refresh"></i> Refresh</a>
				<a class="btn btn-sm btn-default" href="{{ route('treasury.print') }}?key=<?php if(isset($_GET['key'])) echo $_GET['key']; if(isset($_GET['search'])) echo "&search=" . $_GET['search']; ?>" target="_blank"><i class="fa fa-print"></i> Print</a>

				<a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#transmittal" ng-click="loadTransmittalList('{{ route('treasury.email.list') }}')"><i class="fa fa-envelope"></i> Transmittals</a>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4"> 
				<form id="CVForm" action="{{ route('treasury.cv') }}" method="post">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<div class="input-group">
						<div class="input-group-addon">
							<span>Last Check Voucher #</span>
						</div>
						<input type="number" class="form-control" name="last_voucher_number" value="{{ $voucher }}">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default btnSave" data-title="Update Last Generated Check Voucher number?" data-content="Please confirm to continue updating the last generated check voucher number?" data-form="#CVForm"><i class="fa fa-save"></i> Save</button>
						</div>
					</div>

				</form>
			</div>
			<hr>
			<div class="table-responsive" style="height: 100%">
				<div class="form-horizontal ">
					<div class="col-xs-12 col-sm-2 col-md-2">
						<span class="col-xs-12 col-md-3 col-sm-3">
							Show
						</span>
						<?php $show = 0; if(isset($_GET['show'])) $show = $_GET['show']; ?>
						<div class="col-xs-12 col-md-8 col-sm-8">
							<select class="form-control input-sm" id="show" onchange="find()">
								<option value="0"  <?php if($show==0) echo 'selected'; ?>>All</option>
								<option value="10" selected  <?php if($show==10) echo 'selected'; ?>>10</option>
								<option value="20"  <?php if($show==20) echo 'selected'; ?>>20</option>
								<option value="50"  <?php if($show==50) echo 'selected'; ?>>50</option>
								<option value="100"  <?php if($show==100) echo 'selected'; ?>>100</option>
							</select>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<span class="col-xs-12 col-md-3 col-sm-3">
							Status
						</span>
						<?php $status = 'all'; if(isset($_GET['status'])) $status = $_GET['status']; ?>
						<div class="col-xs-12 col-md-8 col-sm-8">
							<select class="form-control input-sm" id="status" onchange="find()">
								<option value="all"  <?php if($status=='all') echo 'selected'; ?>>All</option>
								<option value="5"  <?php if($status=='5') echo 'selected'; ?>>For Approval</option>
								<option value="6"  <?php if($status=='6') echo 'selected'; ?>>Check Releasing</option>
								<option value="7"  <?php if($status=='7') echo 'selected'; ?>>Released</option>
							</select>
						</div>
					</div>
				 	<div class="input-group col-xs-12 col-sm-4 col-md-4 pull-right">
				 		<div class="input-group-btn">
					 		<button class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><span class="dropdown-text">
					 			<?php  
					 				if(isset($_GET['key'])) {
					 					if($_GET['key'] == 'ctrl')
					 						echo 'Control No';
					 					else if($_GET['key'] == 'name')
					 						echo 'Name';
					 					else if($_GET['key'] == 'date')
					 						echo 'Date Applied';
					 					else if($_GET['key'] == 'cv')
					 						echo 'CV No';
					 					else if($_GET['key'] == 'check')
					 						echo 'Check No';
					 					else if($_GET['key'] == 'release')
					 						echo 'Release Date';
					 					else
					 						echo 'Select';
				 					}else
					 						echo 'Select';
					 				
					 			?>
					 		</span> <span class="caret"></span></button>
				 			<input type="hidden" id="field" value="<?php if(isset($_GET['key'])) echo $_GET['key']; ?>">
					 		<ul class="dropdown-menu">
					 			<li><a onclick="setSearchField(this)" value="ctrl">Control No</a></li>
					 			<li><a onclick="setSearchField(this)" value="name">Name</a></li>
					 			<li><a onclick="setSearchField(this)" value="date">Date Applied</a></li>
					 			<li><a onclick="setSearchField(this)" value="cv">CV No</a></li>
					 			<li><a onclick="setSearchField(this)" value="check">Check No</a></li>
					 			<li><a onclick="setSearchField(this)" value="release">Release Date</a></li>
					 		</ul>
				 		</div>
				 		<div id="searchWrapper">
				 			<input type="search" id="search" class="form-control input-sm" style="display: none" placeholder="Search" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
				 			<input type="search" id="search1" class="form-control input-sm"  placeholder="Search" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
				 		</div>
						<a class="input-group-addon btn btn-success btn-sm" onclick="find()"><i class="fa fa-search"></i></a>
				 	</div>
			    </div>
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<th>Control No</th>
						<th>Company</th>
						<th>Employee</th>
						<th>Date Applied</th>
						<th>CV #</th>
						<th>CV Date</th>
						<th>Check #</th>
						<th>Check’s Issue Date</th>
						<th>Check Released Date</th>
						<th>Status</th>
						<th>Action</th>
					</thead>
					<tbody>
						@foreach($loans  as $loan)
						<tr>
							<td>{{ $loan->ctrl_no }}</td>
							<td>{{ $loan->COMPANY }}</td>
							<td>{{ $loan->FullName }}</td>
							<td>{{ date('j F y', strtotime($loan->created_at)) }}</td>
							<td>{{ $loan->cv_no }}</td>
							<td>
								@if(!empty($loan->cv_date))
									{{ $loan->cv_date }}
								@endif
							</td>
							<td>{{ $loan->check_no }}</td>
							<td>
								@if(!empty($loan->check_released))
									{{ $loan->check_released }}
								@endif
							</td>
							<td>
								@if(!empty($loan->released))
									{{ date('j F y h:i A', strtotime($loan->released)) }}
								@endif
							</td>
							<td>{!! $utils->formatTreasuryStatus($loan->status) !!}</td>
							<td>
								<a data-toggle="modal" data-target="#loan" ng-click="loadLoan({{ $loan->id }})" class="btn btn-sm btn-info"><i class="fa fa-eye"></i></a>
								@if(!empty($loan->transmittal_date))
								<i class="fa fa-exchange" title="Transmitted"></i>
								@endif
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
	var $cv = "{{ route('treasury.voucher', 0) }}";
	var searchInputId = '#search';

	function find() {
		var $show = $('#show').val();
		var $key = $('#field').val();
		var $stat = $('#status').val();
		var $search = $(searchInputId).val();
		var $searchUrl = "{{ route('treasury.index') }}" + "?key=" + $key +"&show=" + $show +"&status=" + $stat + "&search=" + $search;
		window.location.href = $searchUrl;
	}

	function checkCVn() {
		if(!$('#check_no')[0].value.trim()){
			$('#cvBtn').prop({disabled: 'disabled'});
		}else{
			$('#cvBtn').prop({disabled: ''});
		}
	}

	function genCV($id) {
		var cn = $('#check_no')[0];

		if(cn.value == ''){
			$('#check_no').closest('.form-group').addClass('has-error');
			alert('Please provide Check No.');
		}else{
			var scope = angular.element($('.row')).scope();
			scope.$apply(function(){
				scope.generateCheckVoucher($id, $('#check_no')[0].value);
			})
		}
	}

	if(tour.ended()){
		var treasuryTourIndex = new Tour({
			name: 'Treasury_Tour_index',
			steps: Treasury_steps_index,
		});

		treasuryTourIndex.init();
		treasuryTourIndex.start();
	}
	var input = $('#search');
	$('#search1').daterangepicker();

	function setSearchField(event) {
		if(event.attributes.value.value == 'ctrl'){
			$('#field')[0].value = event.attributes.value.value;
			$('span.dropdown-text').text(event.text);
			$('searchWrapper').html(input[0]);
			showSearchField(0);

		}else if(event.attributes.value.value == 'name'){
			$('#field')[0].value = event.attributes.value.value;
			$('span.dropdown-text').text(event.text);
			$('searchWrapper').html(input[0]);
			showSearchField(0);

		}else if(event.attributes.value.value == 'date'){
			$('#field')[0].value = event.attributes.value.value;
			$('span.dropdown-text').text(event.text);
			showSearchField(1);

		}else if(event.attributes.value.value == 'cv'){
			$('#field')[0].value = event.attributes.value.value;
			$('searchWrapper').html(input[0]);
			$('span.dropdown-text').text(event.text);
			showSearchField(0);

		}else if(event.attributes.value.value == 'check'){
			$('#field')[0].value = event.attributes.value.value;
			$('searchWrapper').html(input[0]);
			$('span.dropdown-text').text(event.text);
			showSearchField(0);

		}else if(event.attributes.value.value == 'release'){
			$('#field')[0].value = event.attributes.value.value;
			$('span.dropdown-text').text(event.text);
			showSearchField(1);
			
		}
	}

	function showSearchField(int) {
		if(int == 1){
			$('#search').hide();
			$('#search1').show();
			searchInputId = '#search1';
		}else{
			$('#search1').hide();
			$('#search').show();
			searchInputId = '#search';
		}
	}

	$(document).ready(function() {
		$key = '<?php if(isset($_GET['key'])) echo $_GET['key']; ?>';
		$search = '<?php if(isset($_GET['search'])) echo $_GET['search']; ?>';

		if($key == 'date' || $key == 'release'){
			showSearchField(1);
		}else{
			showSearchField(0);
		}
	});
</script> 
@endsection
