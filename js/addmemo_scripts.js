$(document).ready(function() {
  
  $("input[type=checkbox]").change(function() {
    updateMessage();
  })
  
  $("#recipients").change(function() {
    updateMessage();
  })
  
  $("#btnSubmit").click(function() {
    var data = $("#memoForm :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#alert").addClass("alert-danger");
        $("#alert").removeClass("alert-info");
        $("#alert").html("<strong>Stop!</strong> The memo didn't send properly. Try again.");
        console.log(json.message);
      } else if (json.status == 'success') {
        $("#alert").addClass("alert-success");
        $("#alert").removeClass("alert-danger");
        $("#alert").html("<strong>Well done!</strong> Your memo has been sent.");
        $("form").trigger("reset");
        $(".muted-text").each(function() {
          if ($(this).hasClass("text-success")) {
            $(this).removeClass("text-success");
            $(this).addClass("text-danger");
          }
        });
      }
    }, "json");
  })
  
})

function updateMessage() {
  var msg = '';
  $(":checkbox:checked").each(function() {
    msg += $(this).val() + ' ';
  })
  var name = $("#teacher").val();
  var admin = $("#admin").val();
  $("#message").val("You have received an Accountability Memo.\n\n" + msg + '\n\nIf you have any questions, please feel free to ask.\n\nSincerely,\n' + admin);
}