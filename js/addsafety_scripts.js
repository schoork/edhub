$(document).ready(function() {

  var studentTotal = 1;
  $("#addItem").click(function() {
    studentTotal++;
    var html = $("#student_id_div").html();
    html = html.replace(/ 1/g, ' ' + studentTotal);
    html = html.replace(/-1/g, '-' + studentTotal);
    html = '<div class="studentSubDiv">' + html + '</div>';
    $("#studentDiv").append(html);
    $("#removeItem").removeClass("disabled");
    $("#studentTotal").val(studentTotal);
    /*
    $.getJSON("service.php?action=getStudentList", function(json) {
      var html = '<div class="studentSubDiv"><div class="form-group row">';
      html += '<label for="student_id-' + studentTotal + '" class="col-sm-3 col-form-label">Student ' + studentTotal + '</label>';
      html += '<div class="col-sm-9"><select class="form-control" id="student_id-' + studentTotal + '" name="student_id-' + studentTotal + '">';
      html += '<option disabled selected></option>';
      $.each(json.students, function () {
        html += '<option value="' + this.student_id + '">' + this.lastname + ', ' + this.firstname + ' (Gr. ' + this.grade + ')</option>';
      });
      html += '</select><small class="muted-text text-danger required">Required</small></div></div></div>';
      $("#studentDiv").append(html);
      $("#removeItem").removeClass("disabled");
      $("#studentTotal").val(studentTotal);
    });
    */
  });

  $("#removeItem").click(function() {
    if (studentTotal > 1) {
      $("#studentDiv").children(".studentSubDiv").last().remove();
      studentTotal--;
    }
    if (studentTotal == 1) {
      $(this).addClass("disabled");
    }
  });
});
