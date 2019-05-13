$(document).ready(function() {


  $("#course_search").change(function() {
    var value = $("#course_search").val();
    if (value !== '') {
      var string = value.split('-');
      var url = 'attendance.php?id=' + string[0] + '&type=' + string[1];
      window.location.href = url;
    }
  });

  updateRoster();

  $("#btnSubmitForm").click(function() {
    if ($(".muted-text").hasClass("text-danger")) {
      $("#alert").addClass("alert-danger");
      $("#alert").html('<strong>Stop!</strong> Please fill all required fields before submitting form.');
    }
    else {
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
        }
        else if (json.status == 'success') {
          $("#alert").addClass("alert-success");
          $("#alert").removeClass("alert-info");
          $("#alert").html("<strong>Well done!</strong> Your form has been submitted.");
        }
      }, "json");
    }
  });




});

$(document).on('click', '.mark-present', function() {
  var i = $(this).attr("id").substring(8);
  if (i != 'C') {
    $('input.present' + i).prop("checked", true);
  }
  else {
    $('input.period3p').prop("checked", true);
  }
});


function updateRoster() {
  var id = $("#id").val();
  var type = $("#type").val();
  var date = $("#date").val();
  $("#att_tbl tbody").empty();
  $.getJSON("service.php?action=getClassRoster&id=" + id + "&type=" + type + "&date=" + date, function(json) {
    if (json.status == 'no students') {
      $("#nostudents-alert").show();
    }
    else {
      $("#nostudents-alert").hide();
      var html;
      $.each(json.students, function() {
        html = '<tr>';
        html += '<td>' + this.name + '</td>';
        html += '<td><div class="form-check"><label class="form-check-label"><input class="form-check-input period" name="period-' + this.student_id + '" type="radio" value="2"';
        if (this.period == 2) {
          html += ' checked="checked"';
        }
        html += '> Present</label></div><div class="form-check"><label class="form-check-label"><input class="form-check-input" name="period-' + this.student_id + '" type="radio" value="0"';
        if (this.period == 0) {
          html += ' checked="checked"';
        }
        html += '> Absent</label></div><div class="form-check"><label class="form-check-label"><input class="form-check-input" name="period-' + this.student_id + '" type="radio" value="1"';
        if (this.period == 1) {
          html += ' checked="checked"';
        }
        html += '> Tardy</label></div></td>';
        html += '</tr>';
        $("#att_tbl tbody").append(html);
      });
    }
  });
}
