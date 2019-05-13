$(document).on("click", "table tbody tr", function() {
  var id = $(this).prop("id").substr(4);
  window.location.href = 'weeklydatacharts.php?id=' + id;
});
