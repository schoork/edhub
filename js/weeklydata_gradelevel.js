$(document).ready(function() {

  var week = $("#week").val();
  $.getJSON("service.php?action=getGradeLevelChartData&week=" + week, function(json) {
    var data1 = [], data2 =[], labels1 = [];
    $.each(json.ela, function() {
      labels1[labels1.length] = this.grade;
      data1[data1.length] = {meta: this.label, value: this.average};
      data2[data2.length] = {meta: this.label, value: this.prof};
    });
    buildChart(labels1, data1, data2, "#chart1");
    var data1 = [], data2 =[], labels1 = [];
    $.each(json.math, function() {
      labels1[labels1.length] = this.grade;
      data1[data1.length] = {meta: this.label, value: this.average};
      data2[data2.length] = {meta: this.label, value: this.prof};
    });
    buildChart(labels1, data1, data2, "#chart2");
    var data1 = [], data2 =[], labels1 = [];
    $.each(json.other, function() {
      labels1[labels1.length] = this.grade;
      data1[data1.length] = {meta: this.label, value: this.average};
      data2[data2.length] = {meta: this.label, value: this.prof};
    });
    buildChart(labels1, data1, data2, "#chart3");
  });

});

function buildChart(labels, data1, data2, id) {
  var data = {
    labels: labels,
    series: [
      {"name": "Average", "data": data1},
      {"name": "Proficiency", "data": data2}
    ]
  };
  options = {
    axisY: {
      high: 100,
      low: 0
    },
    seriesBarDistance: 70,
    plugins: [
        Chartist.plugins.legend()
    ]
  }
  new Chartist.Bar(id, data, options);
}

$(document).on("click", ".ct-bar", function() {
  var label = $(this).attr('ct:meta');
  var week = $("#week").val();
  label = label.replace(/ /g, '_');
  console.log(label);
  window.location.href = 'weeklydata_classlevel.php?label=' + label + '&week=' + week;
});
