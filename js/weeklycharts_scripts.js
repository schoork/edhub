$(document).ready(function() {

  var id = $("#test_id").val();
  $.getJSON("service.php?action=getWeeklyChartData&id=" + id, function(json) {
    $.each(json.classes, function() {
      buildCharts(this);
    });
  });

});

function buildCharts(data) {
  var class_id = data.class_id;
  //build chart1
  var data_series = [];
  for (var i = 0; i < data.averages.length; i++) {
    data_series[data_series.length] = {x: new Date(data.averages[i].date), y: data.averages[i].average};
  }
  buildChart1(data_series, class_id);
  //build Chart2
  var data = [data.minimal, data.basic, data.pass, data.pro, data.adv];
  buildChart2(data, class_id);
}

function buildChart1(data_series, class_id) {
  var chart = new Chartist.Line('#chart1-' + class_id, {
    series: [
      {
        name: 'series-1',
        data: data_series
      }
    ]
  }, {
    axisX: {
      type: Chartist.FixedScaleAxis,
      divisor: 5,
      labelInterpolationFnc: function(value) {
        return moment(value).format('MMM D');
      }
    }
  }, {
  plugins: [
    Chartist.plugins.ctPointLabels({
      textAnchor: 'middle'
    })
  ]});
}

function buildChart2(series, class_id) {
  var data = {
    labels: ['Minimal', 'Basic', 'Pass', 'Proficient', 'Advanced'],
    series: series
  };

  var options = {
    chartPadding: 15,
    labelOffset: 55,
    labelDirection: 'explode',
    labelInterpolationFnc: function(value) {
      return value
    }
  };


  var sum = function(a, b) { return a + b };

  new Chartist.Pie('#chart2-' + class_id, data, options);
}
