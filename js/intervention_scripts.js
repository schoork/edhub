$(document).ready(function() {

  var id = $("#student_id").val();
  $.getJSON("service.php?action=getStudentInterventions&id=" + id, function(json) {
    if (json.attendance.length > 0) {
      var labels = [], series = [];
      $.each(json.attendance, function() {
        labels[labels.length] = this.date.substr(0, 3);
        series[series.length] = this.absences;
      });
      buildLineChart("#chart-att", labels, series, 'auto')
    }
    if (json.ela.length > 0) {
      var labels = [], series = [];
      $.each(json.ela, function() {
        labels[labels.length] = this.date.substr(0, this.date.length - 5);
        series[series.length] = this.score;
      });
      buildLineChart("#chart-ela", labels, series, 100)
    }
    if (json.math.length > 0) {
      var labels = [], series = [];
      $.each(json.math, function() {
        labels[labels.length] = this.date.substr(0, this.date.length - 5);
        series[series.length] = this.score;
      });
      buildLineChart("#chart-math", labels, series, 100)
    }
    if (json.state_tests.length > 0) {
      var labels = ['2014', '2015', '2016', '2017'];
      var series1 = [json.state_tests[0].math_2014, json.state_tests[0].math_2015, json.state_tests[0].math_2016, json.state_tests[0].math_2017];
      var series2 = [json.state_tests[0].ela_2014, json.state_tests[0].ela_2015, json.state_tests[0].ela_2016, json.state_tests[0].ela_2017];
      buildDblLineChart("#chart-state", labels, series1, series2, 8);
      console.log(labels);
    }
  });

  $("#course_search").on('change', function() {
    var id = $(this).val();
    window.location.href = 'interventions.php?id=' + id;
  });

});

function buildLineChart(chart_id, labels, series, max) {
  new Chartist.Line(chart_id, {
    labels: labels,
    series: [series]
  }, {
    high: max,
    low: 0,
    fullWidth: true,
    showArea: true,
    chartPadding: {
      right: 40
    },
    axisY: {
      onlyInteger: true,
      offset: 20
    }
  });
}

function buildDblLineChart(chart_id, labels, series1, series2, max) {
  var chart = new Chartist.Line(chart_id, {
    labels: labels,
    series: [
      series1,
      series2
    ]
  }, {
    high: max,
    low: 1,
    fullWidth: true,
    chartPadding: {
      right: 40
    },
    axisY: {
      onlyInteger: true,
      offset: 20
    }
  });
}
