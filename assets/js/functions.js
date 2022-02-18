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


$('.btnDelete').on('click', function(event){
	var btn = $(this);
	var title = btn.data('title');
	var content = btn.data('content');
	var url = btn.data('url');

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
});

$('.btnSave').on('click', function(event){
	var btn = $(this);
	var title = btn.data('title');
	var content = btn.data('content');
	var form = btn.data('form');
	var validate = btn.data('validate');

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

});

$('.btnConfirm').on('click', function(event){
	var btn = $(this);
	var title = btn.data('title');
	var content = btn.data('content');
	var url = btn.data('url');
	var validate = btn.data('validate');

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
});
