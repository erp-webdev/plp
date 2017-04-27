var app = angular.module('loanApp', []);
app.controller('LoanCtrl', function($scope, $http) {
	$scope.id 				= $id; 			
	$scope.type 			= $type; 		
	$scope.date 			= $date; 		
	$scope.loc 				= $loc; 		
	$scope.mos	 			= $term; 		
	$scope.loan				= $loan_amount;
	$scope.interest 		= $interest; 	
	$scope.total 			= $total; 		
	$scope.deductions 		= $deductions; 	
	$scope.head 			= $head; 		
	$scope.head_refno 		= $head_refno; 	
	$scope.surety 			= $surety; 		
	$scope.surety_refno 	= $surety_refno;
	$scope.loan_max			= $loan_max;
	$scope.loan_min			= $loan_min;

	$scope.computeTotal = function(){
		$scope.intAmount = $scope.loan * ($scope.interest/100) * $scope.mos;
		$scope.total = $scope.loan + $scope.intAmount;
	};

	$scope.$watchGroup(['loan', 'mos'], function() {
	  	$scope.paymentCtr = $scope.mos * 2;
	  	$scope.computeTotal();
	  	if($scope.id == 0){
			$scope.deductions = $scope.total / $scope.paymentCtr;
		}
		
		if($scope.loan > $scope.loan_min){
			$('#loan_amount').addClass('has-warning');
			$('#surety').removeAttr('style');
			$('#surety_input').attr('required', 'required');
		}else{
			$('#loan_amount').removeClass('has-warning');
			$('#surety').attr('style', 'display:none');
			$('#surety_input').removeAttr('required');
		}
	});

	$scope.$watchGroup(['head', 'surety'], function() {
		$scope.getHead();
		$scope.getSurety();
	});

	$scope.getHead = function(){
		$http({
	        method : "GET",
	        url : $('#getEmployeeURL').val() + "?EmpID=" + $scope.head,
	    }).then(function mySucces(response) {
	        if(response.data){
	        	$('#head_').removeClass('has-error');
	        	$('#head_').addClass('has-success');
	        	$('#head_name').html(response.data);
	        	if($('#id').val() > 0){
	        		$('#verify').removeAttr('disabled');
	        		$('#submit').removeAttr('disabled');
	        	}
	        	else{
	        		$('#verify').removeAttr('disabled');
	        	}
	        }else{
	        	$('#head_').addClass('has-error');
	        	$('#head_').removeClass('has-success');
	        	$('#head_name').html('');
	        	if($('#id').val() > 0){
	        		$('#verify').attr('disabled', 'disabled');
	        		$('#submit').attr('disabled', 'disabled');
	        	}else{
	        		$('#verify').attr('disabled', 'disabled');
	        	}
	        }
	    }, function myError(response) {
	        // $scope.myWelcome = response.statusText;
	    });
	};

	$scope.getSurety = function(){
		$http({
	        method : "GET",
	        url : $('#getEmployeeURL').val() + "?EmpID=" + $scope.surety,
	    }).then(function mySucces(response) {
	        if(response.data){
	        	$('#surety_').removeClass('has-error');
	        	$('#surety_').addClass('has-success');
	        	$('#surety_name').html(response.data);
	        }else{
	        	$('#surety_').addClass('has-error');
	        	$('#surety_').removeClass('has-success');
	        	$('#surety_name').html('');
	        }
	    }, function myError(response) {
	        // $scope.myWelcome = response.statusText;
	    });
	};
	
	$scope.$watchGroup(['loan', 'loc', 'mos', 'interest', 'head', 'surety'], function() {
	    $('#submit').attr('disabled', 'disabled');
	});

});
