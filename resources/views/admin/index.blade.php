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
		@permission(['officer', 'custodian'])
		
		<!-- Yearly Application Statistics -->
		<div class="col-xs-10 col-sm-6 col-md-5" >
			<canvas id="Applications" style="height: 250px"></canvas>
		</div>
		<div class="col-xs-10 col-sm-6 col-md-5" >
			<canvas id="Ranks" style="height: 250px"></canvas>
			<p style="text-align: center; padding-top: 10px" ><a onclick="updateRank(this)" class="btn btn-sm btn-default">Yearly</a></p>
		</div>
		<div class="col-xs-10 col-sm-6 col-md-3" >
			<ul class="list-group">
				<li class="list-group-item active">Application Status</li>
				<li class="list-group-item">
					<span class="badge">{{ $data->stats->inc }}</span>
					Incomplete
				</li>
				<li class="list-group-item">
					<span class="badge">{{ $data->stats->paid }}</span>
					Paid
				</li>
				<li class="list-group-item">
					<span class="badge">{{ $data->stats->approval }}</span>
					For Approval
				</li>
				<li class="list-group-item list-group-item-success">
					<span class="badge">{{ $data->stats->total }}</span>
					Total Applications
				</li>
			</ul>
		</div>
		<div class="col-xs-10 col-sm-6 col-md-6">
			<ul class="list-group">
				<li class="list-group-item active">
					Notifications
				</li>
				<li class="list-group-item">
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 new applications. </p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 new applications for your approval. </p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 new applications for your endorsements.</p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 new applications for your approval as guarantor.</p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 new applications for your payroll verification.</p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 new applications for check preparation.</p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 applications for releasing of check.</p>
					<p><a href="" class="btn-info btn-sm"><i class="fa fa-arrow-right"></i></a> There are 4 applications for  	.</p>
				</li>
			</ul>

		</div>
		@endpermission
		
	</div>
	<div class="row">
		
	</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('/assets/js/Chart.js') }}"></script>
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
	@endpermission
	
	
	 


</script>
@endsection
