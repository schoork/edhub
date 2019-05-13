$(document).ready(function() {

  $(".ct-chart").click(function() {
    var id = $(this).prop("id");
    var height = $(this).outerHeight()*2.5;
    var width = $(this).outerWidth()*2.5;
    var pageWidth = $(window).width();
    var pageHeight = $(window).height();
    var x = $(this).offset();
    var left = (pageWidth/2 - width/2) - x.left;
    var titleBottom = $("#title").offset.top + $("#title").outerHeight();
    var top = titleBottom + (pageHeight/2 - height/2) - x.top - 20;
    $(this).css('background', '#EADBC4').css('z-index', 10);
    $(this).css("position", "absolute").animate({
      left: left,
      top:  top,
      height: height,
      width: width
    }, function() {
      switch(id) {
        case 'chart1':
          setTimeout(buildChart(labels1, data1, data2, "#chart1"), 2000);
          console.log('run');
          break;
        case 'chart2':
          setTimeout(buildChart(labels2, data3, data4, "#chart2"), 2000);
          break;
        case 'chart3':
          setTimeout(buildChart(labels3, data5, data6, "#chart3"), 2000);
          break;
      }
    });

  });

  var week = $("#week").val();
  var data1 = [], data2 =[], labels1 = [];
  var data3 = [], data4 =[], labels2 = [];
  var data5 = [], data6 =[], labels3 = [];
  $.getJSON("service.php?action=getGradeLevelChartData&week=" + week, function(json) {
    $.each(json.ela, function() {
      labels1[labels1.length] = this.grade;
      data1[data1.length] = {meta: this.label, value: this.average};
      data2[data2.length] = {meta: this.label, value: this.prof};
    });
    buildChart(labels1, data1, data2, "#chart1");

    $.each(json.math, function() {
      labels2[labels2.length] = this.grade;
      data3[data3.length] = {meta: this.label, value: this.average};
      data4[data4.length] = {meta: this.label, value: this.prof};
    });
    buildChart(labels2, data3, data4, "#chart2");

    $.each(json.other, function() {
      labels3[labels3.length] = this.grade;
      data5[data5.length] = {meta: this.label, value: this.average};
      data6[data6.length] = {meta: this.label, value: this.prof};
    });
    buildChart(labels3, data5, data6, "#chart3");
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
