$(document).ready(function() {
  var label = $("#label").val();
  var week = $("#week").val();
  $.getJSON("service.php?action=getClassLevelChartData&week=" + week + '&label=' + label, function(json) {
    $.each(json.classes, function() {
      buildCharts(this, "#chart1-" + this.row_id, "#chart2-" + this.row_id);
    });
  });

});

function buildCharts(data, id1, id2) {
  var class_id = data.class_id;
  //build chart1
  var data_series = [];
  for (var i = 0; i < data.averages.length; i++) {
    data_series[data_series.length] = {x: new Date(data.averages[i].date), y: data.averages[i].average};
  }
  buildChart1(data_series, id1);
  //build Chart2
  var data = [data.minimal, data.basic, data.pass, data.pro, data.adv];
  buildChart2(data, id2);
}

function buildChart1(data_series, class_id) {
  var chart = new Chartist.Line(class_id, {
  series: [
    {
      name: 'series-1',
      data: data_series
    }
  ]
  }, {
    axisY: {
      high: 100,
      low: 0
    },
    axisX: {
      type: Chartist.FixedScaleAxis,
      divisor: 5,
      labelInterpolationFnc: function(value) {
        return moment(value).format('MMM D');
      }
    },
    plugins: [
      Chartist.plugins.ctPointLabels()
    ]
  });
}

function buildChart2(series, class_id) {
var data = {
labels: ['Minimal', 'Basic', 'Pass', 'Proficient', 'Advanced'],
series: series
};

var options = {
showLabel: false,
plugins: [
  Chartist.plugins.legend()
]
};


var sum = function(a, b) { return a + b };

new Chartist.Pie(class_id, data, options);
}
