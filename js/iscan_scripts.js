$(document).ready(function() {
  
  enable();
  
  $("#room").on("blur", function() {
    enable();
  });
  
  $("#newScan").click(function() {
    var room = $("#room").val();
    if (room != '') {
      $("#item").attr("disabled", false);
      $("#item").focus();
    }
    else {
      $("#item").attr("disabled", "disabled");
    }
  })
  
  $("#btnDelete").click(function() {
    var data = $("#deleteForm :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#deleteAlert").addClass("alert-danger");
        $("#deleteAlert").removeClass("alert-info");
        $("#deleteAlert").html("<strong>Stop!</strong> The scan didn't delete properly. Try again.");
        console.log(json.message);
      } else if (json.status == 'success') {
        $("#deleteAlert").addClass("alert-success");
        $("#deleteAlert").removeClass("alert-danger");
        $("#deleteAlert").html("<strong>Well done!</strong> This scan has been deleted.");
      }
    }, "json");
  });
  
  $("#item").keypress(function(event){
    if (event.which == '10' || event.which == '13') {
        event.preventDefault();
        setTimeout(function(){ $("form").submit(); }, 300);
    }
});
  
});

function enable() {
  var room = $("#room").val();
  if (room != '') {
    $("#item").attr("disabled", false);
    $("#item").focus();
  }
  else {
    $("#item").attr("disabled", "disabled");
  }
}