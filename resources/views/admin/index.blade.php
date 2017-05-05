@extends('admin.layouts.app')
@section('content')
<style media="screen" style="css">
.box{
	width: 200px;

}
</style>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<?php
				$greetings = '';
				 /* This sets the $time variable to the current hour in the 24 hour clock format */
				 $time = date("H");
				 /* Set the $timezone variable to become the current timezone */
				 $timezone = date("e");
				 /* If the time is less than 1200 hours, show good morning */
				 if ($time < "12") {
						 $greetings = "Good morning, ";
				 } else
				 /* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
				 if ($time >= "12" && $time < "17") {
						 $greetings = "Good afternoon, ";
				 } else
				 /* Should the time be between or equal to 1700 and 1900 hours, show good evening */
				 if ($time >= "17") {
						 $greetings = "Good evening, ";
				 } //else
				 /* Finally, show good night if the time is greater than or equal to 1900 hours */
				//  if ($time >= "19") {
				// 		 $greetings "Good night, ";
				//  }
			 ?>
			 <h2><?php echo $greetings . ucwords(strtolower(Auth::user()->name)) . "!"; ?></h2>
			 <hr>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-4">
			@permission(['officer', 'custodian'])
			<div class="col-xs-12 col-sm-12 col-md-12" >
				<ul class="list-group">
					<li class="list-group-item active">Application Status <?php echo date('Y'); ?></li>
					<li class="list-group-item">
						<span class="badge"><?php if(!empty($data->stats->inc)) echo $data->stats->inc; else echo 0;?></span>
						Active Account
					</li>
					<li class="list-group-item">
						<span class="badge"><?php if(!empty($data->stats->paid)) echo $data->stats->paid; else echo 0;?></span>
						Paid
					</li>
					<li class="list-group-item">
						<span class="badge"><?php if(!empty($data->stats->approval)) echo $data->stats->approval; else echo 0;?></span>
						For Approval
					</li>
					<li class="list-group-item list-group-item-info">
						<span class="badge"><?php if(!empty($data->stats->total)) echo $data->stats->total; else echo 0;?></span>
						Total Applications
					</li>
				</ul>
			</div>
			@endpermission
			@if(count($notifications) > 0)
			<div class="col-xs-12 col-sm-12 col-md-12">
				<div class="list-group" style="">
					<li class="list-group-item active">
						<?php $notifCtr = 0; ?>
						<?php 

							foreach ($notifications as $notif){
								if(empty($notif->seen)){
									$notifCtr++;
								}
							}

						?>
						Notifications
					</li>
					<div class="notifs" style="font-size: 12px; height: 200px; overflow: auto; ">
					@foreach($notifications as $notification)
					  <a onclick="seen(this, {{ $notification->id }})" data-toggle="collapse" href="#notif{{ $notification->id }}" class="list-group-item list-group-item-<?php echo $notification->type; ?>">
					    <h5 class="list-group-item-heading" <?php if(empty($notification->seen)) echo 'style="font-weight: bold"'; ?>>{{ $notification->title }}</h5>
					    <small><time class="timeago" datetime="{{ $notification->created_at }}" title="{{ date('j F Y h:i a', strtotime($notification->created_at)) }}"></time></small>
					    <div class="collapse" id="notif{{ $notification->id }}"><br>
					    	<p class="list-group-item-text">{{ $notification->message }}</p>
					   	</div>
					  </a>
					@endforeach
					</div>
					@if($notifCount > 5)
					<a id="viewMore" class="list-group-item text-center" onclick="viewMore(this)">View More</a>
					@endif
				</div>
			</div>
			@endif
		</div>
		<div id="chartsDiv" class="col-xs-12 col-sm-6 col-md-8">
			<!-- CHARTS -->
			@permission(['officer', 'custodian'])
				@if(count($data->appDatasets) > 0)
				<!-- Yearly Application Statistics -->
				<div class="col-xs-12 col-sm-12 col-md-6" >
					<canvas id="Applications" style="height: 250px"></canvas>
				</div>
				@endif
				@if(count($data->rankDatasets->yearly->labels) > 0)
				<div class="col-xs-12 col-sm-12 col-md-6" >
					<canvas id="Ranks" style="height: 250px"></canvas>
					<p style="text-align: center; padding-top: 10px" ><a onclick="updateRank(this)" class="btn btn-sm btn-default">Yearly</a></p>
				</div>
				@endif
				<div class="col-xs-12 col-sm-12 col-md-6" >
					<canvas id="Income" style="height: 250px"></canvas>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6" >
					<canvas id="IncomeMonthly" style="height: 250px"></canvas>
				</div>
			@endpermission
		</div>
			<p class=""><a id="print" onclick="print()" target="_blank" download="ChartJpg.jpg"><i class="fa fa-print"></i> ad</a></p>
			<p class=""><a href="{{ route('charts') }}" target="_blank"><i class="fa fa-print"></i> Print</a></p>
	</div>
	<div class="row">
		
	</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/Chart.js') }}"></script>
<!-- Timeago by timeago.yarp.com -->
<script src="{{ url('/assets/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
	  jQuery("time.timeago").timeago();
	});

</script>

<script type="text/javascript">
	@permission(['officer', 'custodian'])
	@if(isset($data->appDatasets))
		/*=====================================================
		=            Yearly Application Statistics            =
		=====================================================*/
		
		var ctx = $("#Applications");
		var mos = <?php echo date('n'); ?>;
		var appLabels = <?php 
		 	
			$labels = [];
			$months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		 	for($i = 0; $i < date('n'); $i++){
		 		array_push($labels, $months[$i]);
		 	}

		 	echo json_encode($labels);
		  ?>

		var appChart = new Chart(ctx, {
		    type: 'bar',
		    data: {
		        labels: appLabels,
		        datasets: <?php echo json_encode($data->appDatasets); ?>
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        },
			    title: {
		            display: true,
		            text: 'EFund Applications'
		        },

		    }
		});
		/*=====  End of Yearly Application Statistics  ======*/
	@endif

	@if(isset($data->rankDatasets))

	/*=======================================
	=            Rank Statistics            =
	=======================================*/
	var $rankYearly = <?php echo json_encode($data->rankDatasets->yearly); ?>;
	var $rankTotal = <?php echo json_encode($data->rankDatasets->total); ?>;
	// For a pie chart
	var rankContainer = $("#Ranks");

	var ranks = new Chart(rankContainer,{
	    type: 'pie', //polarArea
	    data: $rankYearly,
	    options: {
	        animation:{
	            animateScale:true
	        },
	        title: {
	            display: true,
	            text: 'EFund Applications By Rank'
	        },
	        legend: {
	        	display: true,
	        	position: 'right',
	        }
	    }	
	});

	function updateRank(event) {
		if($(event).text() == 'Yearly'){
			ranks.config.data = $rankTotal; 
			$(event).text('All');
		}
		else {
			ranks.config.data = $rankYearly; 
			$(event).text('Yearly');
		}

		ranks.update();
	}

	/*=====  End of Rank Statistics  ======*/
	@endif

	@if(isset($data->incomeDatasets))
		var Income = $('#Income');

		var incomeDatasets = new Chart(Income, {
		    type: 'line',
		    data: <?php echo json_encode($data->incomeDatasets); ?>,
		});
	@endif
	@if(isset($data->IncomeMonthlyDatasets))
		var monthly = $('#IncomeMonthly');

		var IncomeMonthlyDatasets = new Chart(monthly, {
		    type: 'line',
		    data: <?php echo json_encode($data->IncomeMonthlyDatasets); ?>,
		});
	@endif
	@endpermission
	
	
	function seen(event, $id) {
		// seen
		$.ajax({
	  	type: "GET",
	  	url: "{{ route('notif.seen', 0) }}" + $id,
	  	data:{'_token': "{{ csrf_token() }}" },
	  	error: function(data){
	    },
	  	success: function(data){
	  		$(event).find('h5').removeAttr('style');
	    },
	  });
	}

	var $notifCtr = 0;
	var $notifCount = {{ $notifCount }};
	function viewMore(event) {
		$notifCtr++;
		// seen
		$.ajax({
	  	type: "GET",
	  	url: "{{ route('notifs', 0) }}" + $notifCtr,
	  	data:{'_token': "{{ csrf_token() }}" },
	  	error: function(data){
	    },
	  	success: function(data){
	  		$('.notifs').append(data);
	  		if(($notifCtr + 1) * 5 >= $notifCount ){
	  			$('#viewMore').hide();
	  		}
	    },
	  });
	}
	 
</script>
@endsection
