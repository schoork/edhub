$(document).ready(function() {

  $("#code").keyup(function() {
    var value = $(this).val();
    value = value.toUpperCase();
    $(this).val(value);
  });

  $("#date").on("blur", function() {
    var date = $(this).val();
    window.location.assign('subapply.php?date=' + date);
  });

  $("#btnApply").click(function() {
    if ($(".muted-text").hasClass("text-danger")) {
      $("#alert").addClass("alert-danger");
      $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form.');
    } else {
        $(this).attr('disabled', true);
      $("#alert").removeClass("alert-danger");
      $("#alert").addClass("alert-info");
      $("#alert").html('<strong>Please Wait!</strong> Your application is being submitted.');
      var data = $("form :input").serializeArray();
      $.post('service.php', data, function(json) {
        if (json.status == 'fail') {
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-danger");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Stop!</strong> Your application didn't update properly. Try again.");
          console.log(json.message);
        } else if (json.status == 'success') {
            $(this).attr('disabled', false);
          $("#alert").addClass("alert-success");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Well done!</strong> You have successfully applied for these positions. If your application is approved, you will recieve a text and email confirmation.");
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

})
