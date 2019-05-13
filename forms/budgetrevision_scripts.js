$(document).ready(function() {
  $("#employee").prop("disabled", "disabled");
});

$(document).on("change", "select", function() {
  var line = $(this).val();
  var id = $(this).prop('id');
  console.log(id);
  $.getJSON("service.php?action=getLineBalance&line=" + line, function(json) {
    var balance = json.balance;
    if (id == 'from_line') {
      $("#from_bal").html(balance);
    }
    else {
      $("#to_bal").html(balance);
    }
  });
});
