$(document).ready(function() {

  $("#week").change(function() {
    var week = $(this).val();
    $("#label-week").html('Week of ' + parseDMY(week));
    $("#chart-subject").empty();
    $("#chart-teacher").empty();
    $("#teacher").html('<option disabled selected>Select a teacher</option>');
    $("#chart-period").empty();
    $("#period").html('<option disabled selected>Select a period</option>');
    $("#chart-student").empty();
    $("#student").html('<option disabled selected>Select a student</option>');
    $.getJSON("service.php?action=getData-Week&week=" + week, function(json) {
      var data1 = [], data2 =[], labels = [];
      labels = json.weeks;
      for (var i = 0; i < labels.length; i++) {
        data1[data1.length] = null;
        data2[data2.length] = null;
      }
      $.each(json.scores, function() {
        var i = labels.indexOf(this.week);
        if (this.test_type == 'Pre-Test') {
          data1[i] = parseFloat(this.score);
        }
        else {
          data2[i] = parseFloat(this.score);
        }
      });
      for (var i = 0; i < labels.length; i++) {
        labels[i] = labels[i].substr(0, labels[i].length - 5);
      }
      buildLineChart(labels, data1, data2, "#chart-week");
      $("#table-subject").empty();
      $("#table-teacher").empty();
      $("#table-teacher2").empty();
    });
  });

  $("#subject").change(function() {
    var subject = $(this).val();
    $("#chart-teacher").empty();
    $("#chart-period").empty();
    var week = $("#week").val();
    $("#period").html('<option disabled selected>Select a period</option>');
    $("#chart-student").empty();
    $("#student").html('<option disabled selected>Select a student</option>');
    $.getJSON("service.php?action=getData-Subject&subject=" + subject + "&week=" + week, function(json) {
      var data1 = [], data2 =[], labels = [];
      labels = json.teachers;
      for (var i = 0; i < labels.length; i++) {
        data1[data1.length] = null;
        data2[data2.length] = null;
      }
      $.each(json.scores, function() {
        var i = labels.indexOf(this.teacher);
        if (this.test_type == 'Pre-Test') {
          data1[i] = parseFloat(this.average);
        }
        else {
          data2[i] = parseFloat(this.average);
        }
      });
      buildBarChart(labels, data1, data2, "#chart-subject", 60);
      var html = '<option disabled selected>Select a teacher</option>';
      for (var i = 0; i < labels.length; i++) {
        html += '<option value="' + labels[i] + '">' + labels[i] + '</option>';
      }
      $("#teacher").html(html);
      html = '<tr><td></td>';
      for (var i = 0; i < labels.length; i++) {
        html += '<td>' + labels[i] + '</td>';
      }
      html += '</tr><tr><td>Pre-Test</td>';
      for (i = 0; i < data1.length; i++) {
        html += '<td>' + Math.round(data1[i]) + '</td>';
      }
      html += '</tr><tr><td>Post-Test</td>';
      for (i = 0; i < data2.length; i++) {
        html += '<td>' + Math.round(data2[i]) + '</td>';
      }
      html += '</tr>';
      $("#table-subject").html(html);
      html = '<tr><td>Course</td><td>Advanced</td><td>Proficient</td><td>Pass</td><td>Basic</td><td>Minimal</td></tr>';
      $.each(json.students, function() {
        html += '<tr><td rowspan="2">' + this.course + '</td>';
        html += '<td>' + this.a + '</td>';
        html += '<td>' + this.b + '</td>';
        html += '<td>' + this.c + '</td>';
        html += '<td>' + this.d + '</td>';
        html += '<td>' + this.f + '</td></tr>';
        html += '<tr><td colspan="2">' + Math.round(this.pro/this.all*100) + '%</td>';
        html += '<td>' + Math.round(this.c/this.all*100) + '%</td>';
        html += '<td>' + Math.round(this.d/this.all*100) + '%</td>';
        html += '<td>' + Math.round(this.f/this.all*100) + '%</td></tr>';
      });
      $("#table-subject2").html(html);
      $("#table-teacher").empty();
      $("#table-teacher2").empty();
    });
  });

  $("#teacher").change(function() {
    $("#chart-period").empty();
    $("#chart-student").empty();
    var week = $("#week").val();
    $("#student").html('<option disabled selected>Select a student</option>');
    var teacher = $(this).val().replace(/ /g, "%20");
    $.getJSON("service.php?action=getData-Teacher&teacher=" + teacher + "&week=" + week, function(json) {
      var data1 = [], data2 =[], labels = [];
      labels = json.periods;
      for (var i = 0; i < labels.length; i++) {
        data1[data1.length] = 0;
        data2[data2.length] = 0;
      }
      $.each(json.scores, function() {
        var i = labels.indexOf(this.period);
        if (this.test_type == 'Pre-Test') {
          data1[i] = parseFloat(this.average);
        }
        else {
          data2[i] = parseFloat(this.average);
        }
      });
      buildBarChart(labels, data1, data2, "#chart-teacher", 70);
      var html = '<option disabled selected>Select a period</option>';
      for (var i = 0; i < labels.length; i++) {
        html += '<option value="' + labels[i].substr(0, 1) + '">' + labels[i].substr(0, 1) + '</option>';
      }
      $("#period").html(html);
      html = '<tr><td></td>';
      for (var i = 0; i < labels.length; i++) {
        html += '<td>' + labels[i] + '</td>';
      }
      html += '</tr><tr><td>Pre-Test</td>';
      for (i = 0; i < data1.length; i++) {
        html += '<td>' + Math.round(data1[i]) + '</td>';
      }
      html += '</tr><tr><td>Post-Test</td>';
      for (i = 0; i < data2.length; i++) {
        html += '<td>' + Math.round(data2[i]) + '</td>';
      }
      html += '</tr>';
      $("#table-teacher").html(html);
      //By grade for students
      html = '<tr><td>Advanced</td><td>Proficient</td><td>Pass</td><td>Basic</td><td>Minimal</td></tr>';
      html += '<tr>';
      html += '<td>' + json.students.a + '</td>';
      html += '<td>' + json.students.b + '</td>';
      html += '<td>' + json.students.c + '</td>';
      html += '<td>' + json.students.d + '</td>';
      html += '<td>' + json.students.f + '</td></tr>';
      html += '<tr><td colspan="2">' + Math.round(json.students.pro/json.students.all*100) + '%</td>';
      html += '<td>' + Math.round(json.students.c/json.students.all*100) + '%</td>';
      html += '<td>' + Math.round(json.students.d/json.students.all*100) + '%</td>';
      html += '<td>' + Math.round(json.students.f/json.students.all*100) + '%</td></tr>';
      $("#table-teacher2").html(html);
      $("#test_ids").val(json.test_ids);
    });
  });

  $("#period").change(function() {
    $("#chart-student").empty();
    var period = $(this).val();
    var week = $("#week").val();
    var teacher = $("#teacher").val().replace(/ /g, "%20");
    $.getJSON("service.php?action=getData-Period&period=" + period + "&teacher=" + teacher + "&week=" + week, function(json) {
      var data1 = [], data2 =[], labels = [];
      labels = json.students;
      for (var i = 0; i < labels.length; i++) {
        data1[data1.length] = 0;
        data2[data2.length] = 0;
      }
      $.each(json.scores, function() {
        var i = labels.indexOf(this.student);
        if (this.test_type == 'Pre-Test') {
          data1[i] = parseFloat(this.score);
        }
        else {
          data2[i] = parseFloat(this.score);
        }
      });
      buildBarChart(labels, data1, data2, "#chart-period", 10);
      var html = '<option disabled selected>Select a student</option>';
      for (var i = 0; i < labels.length; i++) {
        html += '<option value="' + labels[i] + '">' + labels[i] + '</option>';
      }
      $("#student").html(html);
    });
  });

  $("#student").change(function() {
    var student = $(this).val().replace(/ /g, "%20");
    var period = $("#period").val();
    var teacher = $("#teacher").val().replace(/ /g, "%20");
    $.getJSON("service.php?action=getData-Student&student=" + student + "&period=" + period + "&teacher=" + teacher + "&week=" + week, function(json) {
      var data1 = [], data2 =[], labels = [];
      labels = json.dates;
      for (var i = 0; i < labels.length; i++) {
        data1[data1.length] = null;
        data2[data2.length] = null;
      }
      $.each(json.scores, function() {
        var i = labels.indexOf(this.date);
        if (this.test_type == 'Pre-Test') {
          data1[i] = parseFloat(this.score);
        }
        else {
          data2[i] = parseFloat(this.score);
        }
      });
      for (var i = 0; i < labels.length; i++) {
        labels[i] = labels[i].substr(0, labels[i].length - 5);
      }
      buildLineChart(labels, data1, data2, "#chart-student");
    });
  });

});

function buildBarChart(labels, data1, data2, id, distance) {
  var data = {
    labels: labels,
    series: [
      {"name": "Pre-Test", "data": data1},
      {"name": "Post-Test", "data": data2}
    ]
  };
  options = {
    axisY: {
      high: 100,
      low: 0
    },
    seriesBarDistance: distance,
    plugins: [
        Chartist.plugins.legend()
    ]
  }
  new Chartist.Bar(id, data, options);
}

function buildLineChart(labels, data1, data2, id) {
  var chart = new Chartist.Line(id, {
    labels: labels,
    series: [
      {"name": "Pre-Test", "data": data1},
      {"name": "Post-Test", "data": data2}
    ]
  }, {
    fullWidth: true,
    chartPadding: {
      right: 10
    },
    axisY: {
      high: 100,
      low: 0
    },
    plugins: [
        Chartist.plugins.legend()
    ],
    lineSmooth: Chartist.Interpolation.cardinal({
      fillHoles: true,
    }),
    low: 0
  });
}

function parseDMY(value) {
    var date = value.split("-");
    var y = parseInt(date[0], 10),
        m = parseInt(date[1], 10),
        d = parseInt(date[2], 10);
    return m + "/" + d + "/" + y;
}
