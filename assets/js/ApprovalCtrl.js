var app = angular.module('ApprovalApp', []);
app.controller('ApprovalCtrl', function($scope, $http, $filter) {
	$scope.loadLoan = function($id){
	    $('.modal-content').html("<span style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we retrieve the employee's record...</span>");
		$http({
	        method : "GET",
	        url : $showUrl + $id,
	        cache: false,
	    }).then(function mySucces(response) {
	        $('.modal-content').html(response.data);
	    }, function myError(response) {
	        $('.modal-content').html('Something went wrong! Please try again.');
	    });
	};

	$scope.generateCheckVoucher = function($id, $checkCvn){
		$('#cvBtnContainer a').hide();
		$('.loading-cv').show();
		var cn = $checkCvn;
		if(cn.value != ''){
			$http({
				method : "GET",
				url : $cv + $id + "?cn=" + cn,
				cache: false,
			}).then(function mySucces(response){
				$scope.loadLoan($id);
			}, function myError(response){
				$('.checkvoucher').html('Something went wrong! Please try again!')
				$('#cvBtnContainer a').show();
				$('.loading-cv').hide();
			});
		}else{
			$('#check_no').closest('.form-group').addClass('has-error');
			alert('Please provide Check No.');
			return;
		}
	}

	$scope.deductionDate = '';

	$scope.loadBatchDeduction = function($url){

		$('#deductionBatch').html("<span class='col-sm-12' style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we retrieve the employee's record...</span>");
		$http({
	        method : "GET",
	        url : $url + '?deductionDate=' + $filter('date')($scope.deductionDate, 'yyyy-MM-dd'),
	    }).then(function mySucces(response) {
	        $('#deductionBatch').html(response.data);
	        updateARAmount();
	    }, function myError(response) {
	        $('#deductionBatch').html('Something went wrong! Please try again.');
	    });
	};

	$scope.loadPayrollList = function($url){

		$('#emailModalBody').html("<span class='col-sm-12' style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we are retrieving records...</span>");
		
		$http({
	        method : "GET",
	        url : $url,
	    }).then(function mySucces(response) {
			$('#emailModalBody').html(response.data);	
	    }, function myError(response) {
	        $('#emailModalBody').html('Something went wrong! Please try again.');
	    });
	};

	$scope.loadOfficerList = function($url){

		$('#emailOfficerModalBody').html("<span class='col-sm-12' style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we are retrieving records...</span>");
		
		$http({
	        method : "GET",
	        url : $url,
	    }).then(function mySucces(response) {
			$('#emailOfficerModalBody').html(response.data);	
	    }, function myError(response) {
	        $('#emailOfficerModalBody').html('Something went wrong! Please try again.');
	    });
	};

	$scope.loadTransmittalList = function($url){

		$('#emailTreasuryModalBody').html("<span class='col-sm-12' style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we are retrieving records...</span>");
		
		$http({
	        method : "GET",
	        url : $url,
	    }).then(function mySucces(response) {
			$('#emailTreasuryModalBody').html(response.data);	
	    }, function myError(response) {
	        $('#emailTreasuryModalBody').html('Something went wrong! Please try again.');
	    });
	};

});

function loadBatchDeduction($url, event) {
	$('#deductionBatch').html("<span class='col-sm-12' style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we retrieve the employee's record...</span>");
	
	$.ajax({
        type: "GET",
        url: $url + '?deductionDate=' + $(event).val(),
        success: function(response){
            $('#deductionBatch').html(response);
          },
        error:function(response){
           	$('#deductionBatch').html('Something went wrong! Please try again.');
          },
    });
}

function confirm_recalculation($url) {
	if(confirm("Are you sure you want to relcalculate deductions?")){
		startLoading();
    	window.location.href  = $url;
    }  else
    	return false;
}