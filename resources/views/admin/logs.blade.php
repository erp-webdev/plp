@extends('admin.layouts.app')
<style type="text/css">
	.log-box{
		border: 1px solid #ccc;
		font-size: 12px;
		font-family: Arial;
		padding: 5px;
	}
	.msg-box{
		border-width: 1px ;
		border-style: solid;
		border-color: #ccc;
		margin: 5px;
		padding: 5px;
		word-break: break-all;
	}
</style>
@section('content')
<?php 

	function formatMessage($id, $msg)
	{
		$html = '<div class="msg-box">';

		if(is_object($msg)){
			foreach ($msg as $key => $value) {
				$html .= isObject($key, $value);
			}
		}elseif(is_array($msg)){
			$html .= isArray($msg);
		}else{
			$html .= $msg;
		}
		$html .= '</div>';

		return $html;
	}

	function isObject($key, $obj)
	{
		if(is_object($obj)){
			return '<label>' . $key . '</label>' . formatMessage('', $obj);
		}else if(is_array($obj)){
			return '<label>'. $key . '</label> ' . formatMessage('', $obj) .'<br>';
		}else{
			if($key != 'EPassword')
				return '<label>'. $key . '</label> ' .$obj .'<br>';
			else
				return;
		}
	}

	function isArray($array)
	{
		if(is_array($array)){
			$html = '';
			foreach ($array as $key => $value) {
				if(is_array($value)){
					$html .= '<label> Array [' . $key . ']</label> => ' . formatMessage($key, $value);
				}else if(is_object($value)){
					$html .= '<label> Array [' . $key . ']</label> => ' . formatMessage($key, $value);
				}else{
					$html .= '<label> Array[' . $key . ']</label> => ' . $value . '<br>'; 
				}
			}

			return $html;
		}else{
			return formatMessage('', $obj) .'<br>';
		}

	}

 ?>
<div style="display: none">
	<div id="filterDiv">
		<form class="form" action="{{ route('logs.filter') }}" method="get">
			<div class="form-group">
				<small>Date (From - To)</small>
				<input type="date" name="fromDate" placeholder="From Date" class="form-control input-sm" value="<?php if(isset($_GET['fromDate'])) echo $_GET['fromDate']; ?> ">
				<input type="date" name="toDate" placeholder="To Date" class="form-control input-sm" value="<?php if(isset($_GET['toDate'])) echo $_GET['toDate']; ?> ">
			</div>
			<div class="form-group">
				<small>Type</small>
				<select class="form-control input-sm" name="type">
					<option value="All" <?php if(isset($_GET['table'])) if($_GET['type'] == 'All') echo 'selected'; ?>>All</option>
					<option value="insert" <?php if(isset($_GET['table'])) if($_GET['type'] == 'insert') echo 'selected'; ?>>Insert</option>
					<option value="update" <?php if(isset($_GET['table'])) if($_GET['type'] == 'update') echo 'selected'; ?>>Update</option>
					<option value="delete" <?php if(isset($_GET['table'])) if($_GET['type'] == 'delete') echo 'selected'; ?>>Delete</option>
					<option value="info" <?php if(isset($_GET['table'])) if($_GET['type'] == 'info') echo 'selected'; ?>>Info</option>
				</select>
			</div>
			<div class="form-group">
				<small>Table</small>
				<input type="text" name="table" class="form-control input-sm" placeholder="database table" value="<?php if(isset($_GET['table'])) echo $_GET['table']; ?>">
			</div>
			<div class="form-group">
				<small>User</small>
				<input type="text" name="userId" class="form-control input-sm" placeholder="UserId or Name" value="<?php if(isset($_GET['userId'])) echo $_GET['userId']; ?>">
			</div>
			<div class="form-group">
				<small>Content</small>
				<input type="text" name="content" class="form-control input-sm" placeholder="Content" value="<?php if(isset($_GET['content'])) echo $_GET['content']; ?>">
			</div>
			
			<button type="submit" class="btn btn-sm btn-success">Search</button>
		</form>
	</div>
</div>
<div class="container-fluid" >
	<div class="row" style="overflow: scroll; height: 75vh">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="col-xs-12 col-sm-3 col-md-3">
				<span style="font-size: 30px">Logs</span>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-9">
				<a class="btn btn-default btn-sm" href="{{ route('logs') }}">All</a>
				<button id="filterBtn" onclick="loadFilterForm(this)" type="button" class="btn btn-default btn-sm">Filter <i class="fa fa-angle-down"></i></button>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 ">
			@if(count($logs) == 0)
				<span>No Record found!</span>
			@endif

			@foreach($logs as $log)
				<div class="col-sm-3 col-md-3 log-box">
					<label>ID </label> {{ $log->id }}<br>
					<label>Type </label> 
					<?php 
						if($log->type == 'Insert') 
							echo '<label class="label label-success">' . $log->type . '</label>';
						else if($log->type == 'Update') 
							echo '<label class="label label-warning">' . $log->type . '</label>';
						else if($log->type == 'Delete') 
							echo '<label class="label label-danger">' . $log->type . '</label>';
						else if($log->type == 'Info') 
							echo '<label class="label label-info">' . $log->type . '</label>';
						else
							echo '<label class="label label-default">' . $log->type . '</label>';
					 ?>
					<br>
					<label>Table </label> {{ $log->tbl }}<br>
					<label>Created </label> {{ $log->created_at }}<br>
					<label>UserId </label> {{ $log->user_id }} <br>
					<label><a href="#<?php echo $log->id; ?>" data-toggle="collapse">Content <i class="fa fa-angle-down"></i></a></label> 
						<div id="<?php echo $log->id; ?>" class="m">
						<?php $msg = json_decode($log->Message); ?>
						<?php echo formatMessage($log->id, $msg); ?>
						</div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-2 col-md-2">
			<p style="margin-top: 25px;">Showing {{ $logs->count() }} of {{ $logs->total() }} records.</p>
		</div>		
		<div class="col-xs-12 col-sm-10 col-md-10">
				{{ $logs->appends(Input::all())->links() }}
		</div>
	</div>
</div>	

@endsection
@section('scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('.m').addClass('collapse');	
	
	});

	function loadFilterForm(event){

		$(event).popover({
	        title: 'Filter',
	        content: '<p>' +  $('#filterDiv').html() +'</p>',
	        placement: 'bottom',
	        trigger: 'click',
	        html: true
	    }); 
	}

</script>
@endsection