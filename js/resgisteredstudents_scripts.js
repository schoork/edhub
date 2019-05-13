$(document).ready(function() {

  $("#viewHidden").click(function() {
    $(".hidden_row").toggle();
  });

  $("table tbody tr").click(function() {
    var id = $(this).prop("id").substr(8);
    window.location.href = 'viewregistration.php?id=' + id;
  });

});
