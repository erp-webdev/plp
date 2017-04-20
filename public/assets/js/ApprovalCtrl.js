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
		$scope.deductionDate = '';

	$scope.loadBatchDeduction = function($url){
		$('#deductionBatch').html("<span class='col-sm-12' style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we retrieve the employee's record...</span>");
		$http({
	        method : "GET",
	        url : $url + '?deductionDate=' + $filter('date')($scope.deductionDate, 'yyyy-MM-dd'),
	    }).then(function mySucces(response) {
	        $('#deductionBatch').html(response.data);
	    }, function myError(response) {
	        $('#deductionBatch').html('Something went wrong! Please try again.');
	        
	    });
	};

});