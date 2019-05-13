$(document).ready(function() {

  $("#staff_id").change(function() {
    var id = $(this).val();
    id = id.replace(/ /g, "%20");
    $.getJSON("service.php?action=getCourses&teacher=" + id, function(json) {
      var html = '<option disabled selected></option>';
      $.each(json.courses, function() {
        html += '<option value="' + this.course + '">' + this.course + '</option>';
      });
      $("#course-1").html(html);
      $("#course-1").siblings(".required").addClass("text-danger");
      $("#course-1").siblings(".required").removeClass("text-success");
    });
  });

});

$(document).on("change", "input[type='file']", function() {
  var fieldVal = $(this).val();
  console.log(fieldVal);
  fieldVal = fieldVal.substr(12);
  if (fieldVal != undefined || fieldVal != "") {
    fieldVal = fieldVal.substr(0, 20) + '...';
    $(this).next(".custom-file-control").attr('data-content', fieldVal);
  }
});
