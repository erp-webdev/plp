<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Megaworld eFund</title>
    <link rel="stylesheet" href="{{ url('/') }}/assets/css/bootstrap.min.css">
   
</head>
<body id="app-layout">
            <div class="row" id="charts">
		<div class="col-xs-12 col-sm-6 col-md-8">
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
					<!-- <p style="text-align: center; padding-top: 10px" ><a onclick="updateRank(this)" class="btn btn-sm btn-default">Yearly</a></p> -->
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
	</div>    
    <script src="{{ url('/') }}/assets/js/jquery.min.js"></script>
    <script src="{{ url('/') }}/assets/js/bootstrap.min.js"></script>
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
	jQuery(document).ready(function($) {
		window.print();
	});
</script>
</body>
</html>
