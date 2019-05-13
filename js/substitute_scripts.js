$(document).ready(function() {

  $(document).on("click", "tbody tr", function() {
    var id = $(this).attr("id").substring(8);
    var name = $(':nth-child(1)', this).html();
    var location = $(':nth-child(3)', this).html();
    var access = $(':nth-child(4)', this).html();
    $("#staff_id").val(id);
    $("#teacher_name").html(name);
    $("#location").val(location);
    $("#access").val(access);
    $("#formModal").modal('show');
  });

  $(".btnInactive").click(function() {
    $(".hidden-row").toggle();
    $(".btnInactive").toggle();
  });

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
        $("#alert").addClass("alert-success");
        $("#alert").removeClass("alert-info");
        $("#alert").html("<strong>Well done!</strong> The information has been saved.");
      }
    }, "json");
  });

});
