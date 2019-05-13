$(document).ready(function() {

  $("table tbody tr").click(function() {
    var id = $(this).attr("id").substr(5);
    window.location.href = 'viewsafety.php?id=' + id;
  });

});
