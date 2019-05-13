$(document).ready(function() {
  
  $("#type_select").on("change", function() {
    var type = $(this).val();
    window.location.assign('addform.php?type=' + type);
  });
  
});