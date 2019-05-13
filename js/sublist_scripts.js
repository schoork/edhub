$(document).ready(function() {

  $("#date").on("blur", function() {
    var date = $(this).val();
    window.location.assign('sublist.php?date=' + date);
  });

  $("#btnApprove").click(function() {
    if ($(".muted-text").hasClass("text-danger")) {
      $("#alert").addClass("alert-danger");
      $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form.');
    } else {
        $(this).attr('disabled', true);
      $("#alert").removeClass("alert-danger");
      $("#alert").addClass("alert-info");
      $("#alert").html('<strong>Please Wait!</strong> Your approvals are being submitted.');
      var data = $("form :input").serializeArray();
      $.post('service.php', data, function(json) {
        if (json.status == 'fail') {
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-danger");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Stop!</strong> Your approvals didn't update properly. Try again.");
          console.log(json.message);
        } else if (json.status == 'success') {
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-success");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Well done!</strong> You have successfully approved subs for these positions. Each sub was sent a text and email confirmation.");
          $(".muted-text").each(function() {
            if ($(this).hasClass("text-success")) {
              $(this).removeClass("text-success");
              $(this).addClass("text-danger");
            }
          });
        }
      }, "json");
    }
  });

});
