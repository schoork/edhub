$(document).ready(function() {

  $.getJSON("../service.php?action=getSchoolLevelChartData", function(json) {
    var data1 = [], data2 = [], data3 = [], data4 = [];
    for (var i = 0; i < data.schools.length; i++) {
      data1[data_series.length] = {x: new Date(data.averages[i].date), y: data.averages[i].average};
    }
    var chart = new Chartist.Line('#chart1', {
      series: [
        {
          name: 'Sanders - Average',
          data: data_series
        },
        {
          name: 'Sanders - Proficiency',
          data: data_series
        },
        {
          name: 'Simmons - Average',
          data: data_series
        },
        {
          name: 'Simmons - Proficiency',
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
  });

});
