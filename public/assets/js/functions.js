// DataTable
$(document).ready(function() {
   	$('#dataTable').DataTable({
   		"aaSorting": [],	
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