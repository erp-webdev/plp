// DataTable
$(document).ready(function() {
   	$('#dataTable').DataTable({
   		"aaSorting": [],	
      paging : false,
      searching: false,
      // ordering: false
      aaSorting: [],
   	});	
} );
// <!-- Dropdown -->
$(".dropdown-menu li a").click(function(){
  $(this).parents(".dropdown").find('.selection').text($(this).text());
  $(this).parents(".dropdown").find('.selection').val($(this).text());
  $(this).parents(".dropdown").find('.dropValue').val($(this).attr('data-value'));
});
// <!-- Sidebar Menu -->
$("#menu-toggle").click(function(e) {
   	e.preventDefault();
   	$("#wrapper").toggleClass("active");
});
//Auto Alert Close
// $(".alert").fadeTo(5000, 3600).slideUp(500, function(){
//     $(".alert").alert('close');
// });
//Basic Modal
$('#btnShowModal').on('shown.bs.modal', function () {
  $('#accessionNo').focus()
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

function startLoading() {
  $('#loading-wheel').show();
  $('.loading-wheel').removeAttr('style');
}

function stopLoading() {
  $('#loading-wheel').hide();
}

// Copy Function
function copyToClipboard(element) {

  var aux = document.createElement("input");
  aux.setAttribute("value", $(element).text());
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");

  document.body.removeChild(aux);

}

function empty($str) {
	if($str == '' || $str == undefined || $str == null)
		return true;

	return false;
}

$(document).on( 'click', '.btnDelete', function(e){
	var btn = $(this);
	var title = btn.data('title');
	var content = btn.data('content');
	var url = btn.data('url');
	startLoading();

	// defaults
	if(empty(title)){
		title = 'Confirm Delete';
	}

	if(empty(content)){
		content = 'Please confirm to continue!';
	}

	$.confirm({
	    title: title,
	    content: content,
	    buttons: {
	        confirm: {
	            btnClass: 'btn-red',
	            action: function(){
	            	location.href = url;
	            }
	        },
	        cancel: function () {
	            // $.alert('Canceled!');
	        },
	    }
	});

	stopLoading();

});

$(document).on( 'click', '.btnSave', function(e){
	var btn = $(this);
	var title = btn.data('title');
	var content = btn.data('content');
	var form = btn.data('form');
	var validate = btn.data('validate');
	startLoading();

	$(btn).prop({disabled: true});

	if(validate != '' && validate != undefined){

		if(form == undefined || form == null || form == ''){

			var validate_form = $(this).parent('form');
			if(validate_form == undefined || validate_form == null || validate_form == ''){
				// Validate parent form
				if(eval(validate + '('+validate_form+');'))
					return;

		    }else if(!eval(validate + '();')){

				$(btn).prop({disabled: false});
		    	// Validate default;
		    	return;
		    }

		}else{

			// Validate specified form
		    if(!eval(validate + '(\''+form+'\');')){
				$(btn).prop({disabled: false});
		    	return;
			}

		}

	}

	// defaults
	if(empty(title)){
		title = 'Confirm update';
	}

	if(empty(content)){
		content = 'Please confirm to continue!';
	}

	$.confirm({
	    title: title,
	    content: content,
	    buttons: {
	        confirm: {
	            btnClass: 'btn-green',
	            action: function(){
					$('.loader').removeClass('hidden');
	            	if(form == undefined || form == null || form == '')
	            		$(btn).closest('form').submit();
	            	else
	            		$(form).submit();
	            }
	        },
	        cancel: function () {
	            // $.alert('Canceled!');
	        },
	    }
	});
	
	$('.loader').addClass('hidden');
	$(btn).prop({disabled: false});
	stopLoading();

});

$(document).on( 'click', '.btnConfirm', function(e){
	var btn = $(this);
	var title = btn.data('title');
	var content = btn.data('content');
	var url = btn.data('url');
	var validate = btn.data('validate');
	startLoading();

	if(validate != '' && validate != undefined){
		if(!eval(validate + '();'))
			return;
	}

	// defaults
	if(empty(title)){
		title = 'Confirmation';
	}

	if(empty(content)){
		content = 'Please confirm to continue!';
	}

	$.confirm({
	    title: title,
	    content: content,
	    buttons: {
	        confirm: {
	            btnClass: 'btn-blue',
	            action: function(){
	            	location.href = url;
	            }
	        },
	        cancel: function () {
	            // $.alert('Canceled!');
	        },
	    }
	});

	stopLoading();
});

function validate_required(form) {
	var valid = true;
	var tab = '';

	//reset form-group
	$(form).find('.has-error').removeClass('has-error');
	// check all required inputs
	$(form).find('.required').each(function(index, el){
		var label = $(el).attr('for');
		var input = $(el).parent('.form-group').find('.form-control');


		// inputs must have value
		$(input).each(function(i, inp){
			if($.trim($(inp).val()) == ''){
				valid = false;
				console.log(label);
				tab = $(el).closest('.tab-pane').attr('id');
				// highlight element
				$(inp).closest('.form-group').addClass('has-error');
			}
		});
	});

	//
	// $(form).find('input[required=""]').each(function(i, el){
	// 	var tab_ = $(el).closest('.tab-pane').attr('id');
	// 	if(tab_ != '' && tab_ != undefined){

	// 		if($.trim(el.value) == ''){
	// 			valid = false;
	// 				console.log($(el).attr('name'));
	// 			tab = $(el).closest('.tab-pane').attr('id');
	// 			$(el).addClass('has-error');
	// 		}
	// 	}
	// });


	if(!valid){
		$.alert({
		    title: 'Invalid Data',
		    content: 'Check your inputs.',
		});

		// open tab with invalid data
		$('.nav-tabs li[href="#' + tab + '"]').tab('show');
		clickTab();
	}

	return valid;
}

// Validation of standard form with required inputs
function validate_standard(form) {
	var valid = true;
	var tab = '';

	//reset form-group
	$(form).find('.has-error').parent('div').find('.select2-selection').removeAttr('Style');
	$(form).find('.has-error').removeClass('has-error');
	// check all required inputs
	$(form).find('.required').each(function(index, el){
		var label = $(el).attr('for');
		var input = $(el).parent('.form-group').find('.form-control');


		// inputs must have value
		$(input).each(function(i, inp){
			if($.trim($(inp).val()) == ''){
				valid = false;
				// highlight element
				console.log(input.name);
				$(inp).closest('.form-group').addClass('has-error');
			}
		});
		
	});

	$(form).find('input[required=""]').each(function(i, el){

		if($.trim(el.value) == ''){
			valid = false;
			console.log(el.name);
			$(el).addClass('has-error');
		}
	});

	$(form).find('select[required=""]').each(function(i, el){
		$(el).removeClass('has-error');

		if($.trim(el.value) == ''){

			if(el.name != ''){
				valid = false;
				console.log(el.name);
				$(el).addClass('has-error');

				$(el).parent('div').find('.select2-selection').attr({
					style: 'border-color: rgb(185, 74, 72) !important;',
				});

			}
		}

		
	});


	// VALIDATE BANK ACCOUNT NUMBER LENGTH IF UBP
	var bank = $('select[name="BankClass"]').val();

	if(bank == 'UBP'){
		var acctno = $.trim( $('input[name="BankAccountNo"]').val() );
		if( acctno.length != 12 && acctno.length > 0){
			valid = false;
			console.log('BankAccountNo');
			$('input[name="BankAccountNo"]').addClass('has-error');
		}

	}



	if(!valid){
		$.alert({
		    title: 'Invalid Data',
		    content: 'Check your inputs.',
		});
	}

	return valid;
}
