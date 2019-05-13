$(document).ready(function() {
  
  if ($("#location_stored").val() == 'Unknown') {
		$('#formModal').modal('show');
	}
  
  $("#btnSave").click(function() {
    $("#alert").removeClass("alert-danger");
    $("#alert").addClass("alert-info");
    $("#alert").html('<strong>Please Wait!</strong> Your information is being submitted.');
    var data = $("form :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#alert").addClass("alert-danger");
        $("#alert").removeClass("alert-info");
        $("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
        console.log(json.message);
      } else if (json.status == 'success') {
        window.location.replace('logout.php');
      }
    }, "json");
  });
  
});