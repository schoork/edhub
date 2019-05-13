$(document).ready(function() {

  $("#btnSubmit").click(function() {
    if ($(".muted-text").hasClass("text-danger")) {
      $("#alert").addClass("alert-danger");
      $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form.');
    } else {
        $(this).attr('disabled', true);
      $("#alert").removeClass("alert-danger");
      $("#alert").addClass("alert-info");
      $("#alert").html('<strong>Please Wait!</strong> Your information is being submitted.');
      var data = $("form :input").serializeArray();
      $.post('service.php', data, function(json) {
        if (json.status == 'fail') {
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-danger");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
          console.log(json.message);
        } else if (json.status == 'success') {
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-success");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Well done!</strong> Your form has been submitted.");
        }
      }, "json");
    }
  });

});
