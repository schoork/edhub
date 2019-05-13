$(document).ready(function() {

  $(document).click(function() {
      doBounce($("#datapointDiv"), 3, '10px', 300);
  });


  function doBounce(element, times, distance, speed) {
      for(var i = 0; i < times; i++) {
          element.animate({marginTop: '-='+distance}, speed)
              .css({'box-shadow': '10px 10px grey'})
              .animate({marginTop: '+='+distance}, speed)
              .css({'box-shadow': '5px 5px grey'});

      }
  }

  $.getJSON("service.php?action=getSchoolLevelChartData", function(json) {
    var data1 = [], data2 = [], data3 = [], data4 = [];
    $.each(json.schools, function() {
      if (this.school == 'Sanders') {
        data1[data1.length] = {x: new Date(this.week), y: this.average};
        data2[data2.length] = {x: new Date(this.week), y: this.prof};
      }
      else {
        data3[data3.length] = {x: new Date(this.week), y: this.average};
        data4[data4.length] = {x: new Date(this.week), y: this.prof};
      }
    });
    var chart = new Chartist.Line('#chart1', {
      series: [
        { "name": "Sanders Avg", "data": data1},
        { "name": "Sanders Prof", "data": data2},
        { "name": "Simmons Avg", "data": data3},
        { "name": "Simmons Prof", "data": data4}
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
        fullWidth: true,
        chartPadding: {
          right: 40
        },
        plugins: [
          Chartist.plugins.legend()
        ]
    });
  });

});

$(document).on("click", ".ct-point", function() {
  var date = $(this).attr('ct:value').split(",")[0];
  var week = moment(date, "x").add(1, 'days').format("YYYY-MM-DD");
  window.location.href = 'weeklydata_gradelevel.php?date=' + week;
});
