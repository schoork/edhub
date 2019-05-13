$(document).ready(function() {

  restrictAccess();

  var nameLength = $("#name_search").val().length;
  if (nameLength > 0) {
    getInfo();
  }

  $(".class-change").click(function() {
    $(".class-change").addClass("hide");
    var length = $(this).prop("id").length;
    var per = $(this).prop("id").substring($(this).prop("id").search("-") + 1);
    $("#rowid").val(per);
    $("#save-" + per).removeClass("hide");
    $("#del-" + per).removeClass("hide");
    var period = $('#period-' + per).text();
    $("#period-" + per).html('<input type="text" name="period" value="' + period + '">');
    var course = $('#course-' + per).text();
    $("#course-" + per).html('<input type="text" name="course" value="' + course + '">');
    var teacher = $('#teacher-' + per).text();
    $("#teacher-" + per).html('<input type="text" name="teacher" value="' + teacher + '">');
    $.getJSON("service.php?action=getAllTeachers", function(json) {
      var text = '{';
      $.each(json.teachers, function() {
        text += '"' + this.name + '": null, ';
      });
      text = text.substring(0, text.length-2);
      text += '}';
      var obj = JSON.parse(text);
      $("input[name='teacher']").autocomplete({
        data: obj,
        limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
      });
    });
  });

  $(".class-save").click(function() {
    var data = $("#scheduleForm :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        console.log(json.message);
      }
      else {
        var period = $("input[name='period']").val();
        $("input[name='period']").parent().html(period);
        var course = $("input[name='course']").val();
        $("input[name='course']").parent().html(course);
        var teacher = $("input[name='teacher']").val();
        $("input[name='teacher']").parent().html(teacher);
        $(".class-change").removeClass("hide");
        $(".class-save").addClass("hide");
        $(".class-del").addClass("hide");
      }
    }, "json");
  });

  $(".class-del").click(function() {
    var id = $(this).prop("id");
    $("#scheduleAction").val('deleteSchedule');
    var data = $("#scheduleForm :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        console.log(json.message);
      }
      else {
        $("#" + id).parent().parent().remove();
        $(".class-change").removeClass("hide");
        $(".class-save").addClass("hide");
        $(".class-del").addClass("hide");
      }
    }, "json");
    $("#scheduleAction").val('updateSchedule');
  });

  $("#btnAddClass").click(function() {
    $(this).addClass("hide");
    $(".class-change").addClass("hide");
    var per = 'z';
    $("#rowid").val(per);
    $("#tbl_schedule tbody").append('<tr><td><a class="waves-effect waves-light btn-floating btn-tiny class-save" id="save-' + per + '"><i class="material-icons">save</i></a><a class="waves-effect waves-light btn-floating btn-tiny class-del red" id="del-' + per + '"><i class="material-icons">delete</i></a></td><td><input type="text" name="period"></td><td><input type="text" name="course"></td><td><input type="text" name="teacher"></td>');
    $.getJSON("service.php?action=getAllTeachers", function(json) {
      var text = '{';
      $.each(json.teachers, function() {
        text += '"' + this.name + '": null, ';
      });
      text = text.substring(0, text.length-2);
      text += '}';
      var obj = JSON.parse(text);
      $("input[name='teacher']").autocomplete({
        data: obj,
        limit: 20, // The max amount of results that can be shown at once. Default: Infinity.
      });
    });

    $(".class-save").click(function() {
      var data = $("#scheduleForm :input").serializeArray();
      $.post('service.php', data, function(json) {
        if (json.status == 'fail') {
          console.log(json.message);
        }
        else {
          var period = $("input[name='period']").val();
          $("input[name='period']").parent().html(period);
          var course = $("input[name='course']").val();
          $("input[name='course']").parent().html(course);
          var teacher = $("input[name='teacher']").val();
          $("input[name='teacher']").parent().html(teacher);
          $(".class-change").removeClass("hide");
          $(".class-save").addClass("hide");
          $(".class-del").addClass("hide");
          $("#btnAddClass").removeClass("hide");
        }
      }, "json");
    });
  })

  //Check age of uploads, alert if needed.
  $.getJSON("service.php?action=getUploadDates", function(json) {
    if (json.status == 'expired') {
      $('#modal1').openModal();
    }
  });

  $.getJSON("service.php?action=getStudents", function(json) {
    var text = '{';
    $.each(json.students, function() {
      text += '"' + this.name + '": null, ';
    });
    text = text.substring(0, text.length-2);
    text += '}';
    var obj = JSON.parse(text);
    $('input.autocomplete').autocomplete({
      data: obj
    });
  });

  $("#name_search").on('blur', function() {
    var delay = 200;
    setTimeout(function() {
      var nameLength = $("#name_search").val().length;
      var id = $("#name_search").val().substring(nameLength - 7, nameLength - 1);
      window.location.href = 'studentdash.php?id=' + id;
    }, delay);
  })

  $("select").material_select();

  $("#btnSubmit").click(function() {
    var data = $("#checklist_form :input").serializeArray();
    $.post('service.php', data, function(json) {
      if (json.status == 'fail') {
        $("#error_div").html('Error: ' + json.message);
      }
      else {
        Materialize.toast('Checklist updated!', 3000);
      }
    }, "json");
  })

});


function getInfo() {
  var delay = 200;
  setTimeout(function() {
    var nameLength = $("#name_search").val().length;
    var id = $("#name_search").val().substring(nameLength - 7, nameLength - 1);
    var name = $("#name_search").val();
    $("#student_id").val(id);
    $("#btnUpload").prop("href", "upload_rtidocuments.php?id=" + id);
    $.getJSON("service.php?action=getStudentInfo&id=" + id, function(json) {
      if (json.status == 'success') {

        //Adds to Checklist Tab
        $.each(json.checked_items, function() {
          var id = this.item;
          $("#checklist-" + id).prop("checked", true);
          $("#check-" + id).html('<em>Marked Complete on ' + this.date + '</em>');
        })

        //Adds to Data Tracking Tab
        var data1 = [], data2 = [], data3 = [], labels1 = [], labels2 = [], labels3 = [];
        $.each(json.data_tracks, function() {
          switch (this.target_beh) {
            case '1':
              labels1[labels1.length] = moment(this.date, "M/D/YYYY").format("M/D");
              data1[data1.length] = this.data3;
              break;
            case '2':
              labels2[labels2.length] = moment(this.date, "M/D/YYYY").format("M/D");
              data2[data2.length] = this.data3;
              break;
            case '3':
              labels3[labels3.length] = moment(this.date, "M/D/YYYY").format("M/D");
              data3[data3.length] = this.data3;
              break;
          }
        });
        buildLineChart(labels1, labels2, labels3, data1, data2, data3);

        //Adds to Behavior Tracker Data tab
        var data1 = [], data2 = [], data3 = [], data4 = [], data5 = [], data6 = [], labels = [];
        $.each(json.teacher_dates, function() {
          labels[labels.length] = this.date;
        });
        $.each(json.teacher_inputs, function() {
          var i = labels.indexOf(this.date);
          switch (this.behavior) {
            case '1':
              data1[i] = this.avg;
              break;
            case '2':
              data2[i] = this.avg;
              break;
            case '3':
              data3[i] = this.avg;
              break;
            case '4':
              data4[i] = this.avg;
              break;
            case '5':
              data5[i] = this.avg;
              break;
            case '6':
              data6[i] = this.avg;
              break;
          }
        });
        buildChart(labels, data1, data2, data3, data4, data5, data6);
      }
      else {
        Materialize.toast(json.message, 3000);
      }
    });
  }, delay);
  restrictAccess();
}

$(document).on("click", ".remove-button", function() {
  var id = $(this).attr("id").substring(7);
  $("#remove_doc").val(id);
  $("#modal2").openModal();
});

$(document).on("click", "#btnDeleteDocument", function() {
  var id = $("#remove_doc").val();
  $.getJSON("service.php?action=removeDocument&id=" + id, function(json) {
    if (json.status == 'fail') {
      Materialize.toast('Error: Try again. If the problem persists, contact the webmaster.', 6000);
    }
    else {
      $('.tooltipped').tooltip('remove');
      $("#remove-" + id).parent().parent().remove();
      $('.tooltipped').tooltip({delay: 50});
    }
  });
});

$(document).on("change", ".filled-in", function() {
  var nameLength = $("#name_search").val().length;
  var id = $("#name_search").val().substring(nameLength - 7, nameLength - 1);
  var teachers = '';
  $(".filled-in").each(function() {
    if ($(this).prop("checked") === true) {
      teachers += $(this).val() + '; ';
    }
  });
  teachers = teachers.substr(0, teachers.length - 2);
  $.getJSON("service.php?action=getTeacherTrackerData&id=" + id + "&teachers=" + teachers, function(json) {
    var data1 = [], data2 = [], data3 = [], data4 = [], data5 = [], data6 = [], labels = [];
    $.each(json.teacher_dates, function() {
      labels[labels.length] = this.date;
    });
    $.each(json.teacher_inputs, function() {
        var i = labels.indexOf(this.date);
        switch (this.behavior) {
          case '1':
            data1[i] = this.avg;
            break;
          case '2':
            data2[i] = this.avg;
            break;
          case '3':
            data3[i] = this.avg;
            break;
          case '4':
            data4[i] = this.avg;
            break;
          case '5':
            data5[i] = this.avg;
            break;
          case '6':
            data6[i] = this.avg;
            break;
        }
    });
    $("#myChartDiv").html('<canvas id="myChart" width="200" height="150"></canvas>');
    buildChart(labels, data1, data2, data3, data4, data5, data6);
  });
});

function buildChart(labels, data1, data2, data3, data4, data5, data6) {
  var colors = [
    ['rgba(0,150,134,0.4)', 'rgba(0,150,134,1)'],
    ['rgba(235,0,17,0.4)', 'rgba(235,0,17,1)'],
    ['rgba(241,156,0,0.4)', 'rgba(241,156,0,1)'],
    ['rgba(33, 152, 243, 0.4)', 'rgba(33, 152, 243, 1)'],
    ['rgba(255, 86, 19, 0.4)', 'rgba(255, 86, 19, 1)'],
    ['rgba(255, 196, 19, 0.4)', 'rgba(255, 196, 19, 1)']
  ];
  var datasets = [];
  var i = 0;
  if ($.isEmptyObject(data1) === false) {
    i++;
    datasets[i-1] = {
      label: "Replacement Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data1,
      spanGaps: false,
    };
  }
  if ($.isEmptyObject(data2) === false) {
    i++;
    datasets[i-1] = {
      label: "Replacement Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data2,
      spanGaps: false,
    };
  }
  if ($.isEmptyObject(data3) === false) {
    i++;
    datasets[i-1] = {
      label: "Replacement Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data3,
      spanGaps: false,
    };
  }
  if ($.isEmptyObject(data4) === false) {
    i++;
    datasets[i-1] = {
      label: "Replacement Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data4,
      spanGaps: false,
    };
  }
  if ($.isEmptyObject(data5) === false) {
    i++;
    datasets[i-1] = {
      label: "Replacement Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data5,
      spanGaps: false,
    };
  }
  if ($.isEmptyObject(data6) === false) {
    i++;
    datasets[i-1] = {
      label: "Replacement Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data6,
      spanGaps: false,
    };
  }
  var ctx = $("#myChart");
  var data = {
    labels: labels,
    datasets: datasets
  };
  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: data,
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    suggestedMax: 4,
                    min: 0,
                    stepSize: 0.5
                }
            }]
        }
    }
  });
}

function buildLineChart(labels1, labels2, labels3, data1, data2, data3) {
  var colors = [
    ['rgba(0,150,134,0.4)', 'rgba(0,150,134,1)'],
    ['rgba(235,0,17,0.4)', 'rgba(235,0,17,1)'],
    ['rgba(241,156,0,0.4)', 'rgba(241,156,0,1)']
  ];
  var i = 0;
  i++;
  if (data1.length > 0) {
    var datasets = [{
      label: "Target Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data1,
      spanGaps: false,
    }];
    var ctx1 = $("#chart1");
    var data = {
      labels: labels1,
      datasets: datasets
    };
    var myLineChart = new Chart(ctx1, {
      type: 'line',
      data: data
    });
  }
  i++;
  if (data2.length > 0) {
    var datasets = [{
      label: "Target Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data2,
      spanGaps: false,
    }];
    var ctx2 = $("#chart2");
    var data = {
      labels: labels2,
      datasets: datasets
    };
    var myLineChart = new Chart(ctx2, {
      type: 'line',
      data: data
    });
  }
  i++;
  if (data3.length > 0) {
    var datasets = [{
      label: "Target Behavior " + i,
      fill: false,
      lineTension: 0.1,
      backgroundColor: colors[i-1][0],
      borderColor: colors[i-1][1],
      borderCapStyle: 'butt',
      borderDash: [],
      borderDashOffset: 0.0,
      borderJoinStyle: 'miter',
      pointBorderColor: colors[i-1][1],
      pointBackgroundColor: "#fff",
      pointBorderWidth: 1,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: colors[i-1][1],
      pointHoverBorderColor: "rgba(220,220,220,1)",
      pointHoverBorderWidth: 2,
      pointRadius: 3,
      pointHitRadius: 10,
      data: data3,
      spanGaps: false,
    }];
    var ctx3 = $("#chart3");
    var data = {
      labels: labels3,
      datasets: datasets
    };
    var myLineChart = new Chart(ctx3, {
      type: 'line',
      data: data
    });
  }
}

function restrictAccess() {
  var access = $("#staff_access").val();
  console.log(access);
  if (access != 'Admin') {
    $("form :input").prop("disabled", true);
    $(".btn").addClass("disabled");
    $(".btn-floating").addClass("disabled");
  }
  if (access == 'CaseManager') {
    $("#btnEditSch").removeClass("disabled");
    $("#btnUpSch").removeClass("disabled");
  }
}
