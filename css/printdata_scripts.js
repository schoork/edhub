$(document).ready(function() {

  var week = $("#week").val();
  $.getJSON("service.php?action=getPrintData&week=" + week, function(json) {
    $.each(json.teachers, function() {
      var data1 = [], data2 =[], labels = [], courses = [];
      labels = this.periods;
      courses = this.courses;
      for (var i = 0; i < labels.length; i++) {
        data1[data1.length] = 0;
        data2[data2.length] = 0;
      }
      $.each(this.scores, function() {
        var i = labels.indexOf(this.period);
        if (this.test_type == 'Pre-Test') {
          data1[i] = parseFloat(this.average);
        }
        else {
          data2[i] = parseFloat(this.average);
        }
      });
      var row_id = this.row_id;
      buildBarChart(labels, data1, data2, "#chart-" + row_id, 60);
      //Adds data to class table
      var html = '';
      for (var i = 0; i < labels.length; i++) {
        html += '<tr>';
        html += '<td>' + labels[i] + '</td>';
        html += '<td>' + courses[i] + '</td>';
        html += '<td>' + Math.round(data1[i]) + '</td>';
        html += '<td>' + Math.round(data2[i]) + '</td>';
        var growth = Math.round(data2[i]) - Math.round(data1[i]);
        html += '<td>' + growth + '</td>';
        html += '</tr>';
      }
      $("#classesTable-" + row_id + " tbody").html(html);
      //adds data to proficiency tables
      var html = '';
      var a = 0, b = 0, c = 0, d = 0, f = 0, pro = 0, all = 0;
      $.each(this.students, function() {
        html += '<tr>';
        html += '<td>' + this.period + '</td>';
        html += '<td>' + this.course + '</td>'
        html += '<td>' + this.a + '</td>';
        html += '<td>' + this.b + '</td>';
        html += '<td>' + this.c + '</td>';
        html += '<td>' + this.d + '</td>';
        html += '<td>' + this.f + '</td>';
        html += '</tr>';
        a = a + this.a;
        b = b + this.b;
        c = c + this.c;
        d = d + this.d;
        f = f + this.f;
        pro = pro + this.a + this.b;
        all = all + this.a + this.b + this.c + this.d + this.f;
      });
      html += '<tr>';
      html += '<td colspan="2" rowspan="2">Overall</td>';
      html += '<td>' + a + '</td>';
      html += '<td>' + b + '</td>';
      html += '<td>' + c + '</td>';
      html += '<td>' + d + '</td>';
      html += '<td>' + f + '</td>';
      html += '</tr>';
      html += '<tr>';
      html += '<td colspan="2">' + Math.round(pro/all*100) + '%</td>';
      html += '<td>' + Math.round(c/all*100) + '%</td>';
      html += '<td>' + Math.round(d/all*100) + '%</td>';
      html += '<td>' + Math.round(f/all*100) + '%</td></tr>';
      console.log(all);
      $("#profTable-" + row_id + " tbody").html(html);
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
  var chart = new Chartist.Bar(id, data, options);
}
