$(document).ready(function() {

  addWeeks();
  $("select").material_select();

  $("#student_id").change(function() {
    updateTeachers();
  });

  $("#week").change(function() {
    $("#week_hidden").val($(this).val());
    updateTeachers();
  });

  $("#btnSubmit").click(function() {
    $("#error_div").empty();
    var data = $("#tracker_form :input").serializeArray();
    $.post('service.php', data, function(json) {
      $("#error_div").html('Data successfully updated!');
      Materialize.toast("Data successfully updated!", 3000);
      $("#change_div").addClass("hide");
    }, "json");
  });

})

function addWeeks() {
  var firstDay = moment('2016-08-08');
  var i = 0;
  var html = '';
  while (moment().day(1 - 7*i) >= firstDay) {
    html += '<option value="' + moment().day(1 - 7*i).format('YYYY-MM-DD') + '">Week of ' + moment().day(1 - 7*i).format('MMM DD') + '</option>';
    i++;
  }
  $("#week").append(html);
}

function updateTeachers() {
  $("#teacher_section").empty();
  var monday = $("#week").val();
  var student_id = $("#student_id").val();
  $.getJSON("service.php?action=getStudentTrackerInfo&monday=" + monday + "&student=" + student_id, function(json) {
    $.each(json.teachers, function() {
      var html = '<div class="row"><div class="col s12 grey lighten-2"><h5>Period ' + this.period + ' - ' + this.teacher + '</h5></div></div>';
      html += '<div class="row"><div class="col s12 m4">0 = Behavior not demonstrated (0%)<br/>1 = Behavior poorly demonstrated (1%-25%)</div><div class="col s12 m4">2 = Behavior somewhat demonstrated (26%-50%)<br/>3 = Behavior adequately demonstrated (51%-75%)</div><div class="col s12 m4">4 = Behavior largely demonstrated (76%-100%)</div></div>';
      html += '<div class="row"><div class="col s12"><table class="bordered"><thead><tr><th class="first-col">Replacement Behaviors</th>';
      for (var j = 1; j < 6; j++) {
        var date = moment(monday, "YYYY-MM-DD").day(j).format('M/D');
        html += '<th>' + date + '</th>';
      }
      html += '</tr></thead><tbody>';
      for (var k = 1; k < 7; k++) {
        if (json.rep_behs['replace' + k] !== null && json.rep_behs['replace' + k] != '') {
          html += '<tr><td>' + json.rep_behs['replace' + k] + '</td>';
          for (j = 1; j < 6; j++) {
            //teacher-day-behavior
            html += '<td><select name="input-' + this.period + '-' + j + '-' + k + '" id="select-' + this.period + '-' + j + '-' + k + '"><option selected></option><option value="0">0</option><option value="1">1</option><option value="2">2</option>';
            html += '<option value="3">3</option><option value="4">4</option>';
            html += '<option value="100">Absent</option><option value="101">Skipped</option><option value="102">Suspended</option><option value="103">Teacher Absent</option><option value="104">No Class</option><option value="105">Not Applicable</option></select></td>';
          }
          html += '</tr>';
        }
      }
      html += '</tbody></table></div></div>';
      $("#teacher_section").append(html);
    })
    $.each(json.inputs, function() {
      $("select[name='" + this.name + "']").val(this.value);
    })
    $("select").material_select();
  });
}

$(document).on("change", "select", function() {
  if ($(this).attr("id") != 'week' && $(this).attr("id") != 'student_id') {
    $("#change_div").removeClass("hide");
  }
  $("#error_div").empty();
  if ($(this).val() >= 100) {
    var value = $(this).val();
    var name = $(this).attr("name");
    for (var i = 1; i < 7; i++) {
      var str = name.substr(0, name.length - 1);
      $("select[name='" + str + i + "']").val(value);
    }
    $('select').material_select();
  }
});
