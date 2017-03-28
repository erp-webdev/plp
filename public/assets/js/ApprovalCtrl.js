var app = angular.module('ApprovalApp', []);
app.controller('ApprovalCtrl', function($scope, $http) {
	$scope.loadLoan = function($id){
	    $('.modal-content').html("<span style='padding: 10px'><i class='fa fa-spin fa-spinner'></i> Please wait while we retrieve the employee's record...</span>");
		$http({
	        method : "GET",
	        url : $showUrl + $id,
	    }).then(function mySucces(response) {
	        $('.modal-content').html(response.data);
	    }, function myError(response) {
	        $('.modal-content').html('Something went wrong! Please try again.');
	        
	    });
	};

});