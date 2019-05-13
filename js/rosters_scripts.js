$(document).ready(function() {

  $("#course_search").change(function() {
    var value = $("#course_search").val();
    if (value !== '') {
      var string = value.split('-');
      var url = 'rosters.php?id=' + string[0] + '&type=' + string[1];
      window.location.href = url;
    }
  });

  /*
  $('tbody > tr').click(function() {
    var id = $(this).attr("id").substring(8);
    window.location = 'studentpage.php?id=' + id;
  });
  */
});
