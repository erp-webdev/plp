function showReport(event, format) {
		displayFilterMenu($type);

		if($type == ''){
			$('#reportView').html('<div class="alert alert-danger">Please select report to be generated.</div>');
			return;
		}else if($type == 'deduction'){
			if($('input[name="payroll"]').val() == ''){
				$('#reportView').html('<div class="alert alert-danger">Please specify payroll cutoff at the filter menu.</div>');
				return;
			}
		}else if($type == 'fullypaid'){
			if($('input[name="endDeduction"]').val() == ''){
				$('#reportView').html('<div class="alert alert-danger">Please specify end of amortization schedule.</div>');
				return;
			}
		}

		startLoading();

		var url = showUrl + "/" + $type + '?format='+format+'&_token=' + _token + "&" + $('.filterElements').serialize();

		if(format == 'view')
		{
			// Preview
			$.ajax({
		        type: "GET",
		        url: url,
		        success: function(data){

		            $('#reportView').html(data);

					stopLoading();
		          },
		        error:function(data){
		            $('#reportView').html('<span class="text-danger">Something went wrong! Please try again!</span>');
					stopLoading();
		          },
		    });
		}else{
			// Printing
			window.open(url, '_blank');
		}

		stopLoading();
	}

	function displayFilterMenu(type) {
		var filters = {
			payroll: [
				'',
				'control',
				'EmpID',
				'empName', 
				'checkRelease', 
				'totalAmount', 
				'totalDeduction', 
				'deductionPerPayday', 
				'startDeduction', 
				'created_at',
				'toDate',
				'sort',
				'status'
			],

			loan_summary: [
				'control',
				'EmpID',
				'empName', 
				'checkRelease', 
				'totalAmount', 
				'totalDeduction', 
				'deductionPerPayday', 
				'startDeduction', 
				'created_at',
				'sort',
				'guarantor',
				'cvno',
				'cvdate',
				'checkno',
				'principal',
				'status',
				'interest',
				'special',
				'surety'
			],

			monthly: [
				'created_at'
			],

			cutoff: [
				'sort',
				'payroll'
			],

			resigned:[
			],

			fullypaid:[
				'endDeduction', 
			]
		};


		// Hide or display elements for filter
		var input = $('.filterElements').find('input, select')
		var filter  = [];

		if(type == 'payroll'){
			$('#reportTypeDisp').val('Payroll')
			filter = filters.payroll
		}else if(type == 'summary'){
			$('#reportTypeDisp').val('Loan Summary')
			filter = filters.loan_summary
		}else if(type == 'monthly'){
			$('#reportTypeDisp').val('Monthly Summary')
			filter = filters.monthly
		}else if(type == 'deduction'){
			$('#reportTypeDisp').val('Cutoff Without Deduction')
			filter = filters.cutoff
		}else if(type == 'resigned'){
			$('#reportTypeDisp').val('Resigned Employees with Balance')
			filter = filters.resigned
		}else if(type == 'fullypaid'){
			$('#reportTypeDisp').val('Last Amortization Schedule')
			filter = filters.fullypaid
		}

		if(filter.length > 0){
			$('#filterBtn').attr('disabled', false)

			for(var i = 0; i < input.length; i++){
				if(inArray(input[i].name, filter)){
					$(input[i]).closest('.form-group').show()
				}else{
					$(input[i]).closest('.form-group').hide()
				}
			}
		}else{
			$('#filterBtn').attr('disabled', true)
		}
	}

	$('.clear').on('click', function(event) {
		var input = $('.filterElements').find('input')
		for(var i = 0; i < input.length; i++){
			$(input[i]).val('')
		}
	}); 
		
	function inArray(str, arr) {
		for(var i = 0; i < arr.length; i++){
			if(str == arr[i])
				return true
		}

		return false
	}

	// function print() {
	// 	if($type == ''){
	// 		$('#reportView').html('<div class="alert alert-danger">Please select report to be generated.</div>');
	// 		return;
	// 	}

	// 	var url = printUrl + "/" + $type + 
	//         		"?dateFrom=" + $('#fromDate').val() +
	//         		"&dateTo=" + $('#toDate').val() +
	//         		"&EmpID=" + $('#EmpID').val() +
	//         		"&status=" + $('#status').val() +
	//         		"&format=" + format +
	//         		'&_token=' + "{{ csrf_token() }}";

	//     window.open(url, '_blank');
	// }

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