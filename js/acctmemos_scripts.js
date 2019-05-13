$(document).ready(function() {
  
  $(document).on("click", "tbody tr", function() {
    var id = $(this).attr("id").substring(5);
    $("#memo_id").html(id);
    $("#memoId").val(id);
    var sender = $(':nth-child(2)', this).html();
    var recipient = $(':nth-child(3)', this).html();
    var date = $(':nth-child(1)', this).html();
    $("#date").html(date);
    $("#sender").html(sender);
    $("#recipient").html(recipient);
    $.getJSON( "service.php?action=getMemo&memoId=" + id, function(json) {
      $("#message").html(json.message);
    });
    $("#formModal").modal('show');
  });
 
  $("#btnDelete").click(function() {
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