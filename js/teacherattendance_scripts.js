$(document).ready(function() {

  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  $.getJSON("service.php?action=getTeacherAttendanceChart", function(json) {
    var data1 = [], data2 = [];
    for (var i=0; i<months.length; i++) {
      data1[months[i]] = 0;
    }
    $.each(json.totals, function() {
      if (this.school == 'Sanders') {
        data1[this.month - 1] = this.number;
      }
      else {
        data2[this.month - 1] = this.number;
      }
    });
    var chart = new Chartist.Bar('#chart1', {
      series: [
        { "name": "Sanders", "data": data1},
        { "name": "Simmons", "data": data2}
      ],
      labels: months
      }, {
        fullWidth: true,
        chartPadding: {
          right: 40
        },
        plugins: [
          Chartist.plugins.legend()
        ]
    });
    var series = [], labels = [];
    $.each(json.types, function() {
      series[series.length] = this.number;
      labels[labels.length] = this.type;
    });
    var data = {
      series: series,
      labels: labels
    };

    var options = {
      labelInterpolationFnc: function(value) {
        return value[0]
      }
    };

    var responsiveOptions = [
      ['screen and (min-width: 640px)', {
        chartPadding: 30,
        labelOffset: 100,
        labelDirection: 'explode',
        labelInterpolationFnc: function(value) {
          return value;
        }
      }],
      ['screen and (min-width: 1024px)', {
        labelOffset: 140,
        chartPadding: 20
      }]
    ];

    new Chartist.Pie('#chart2', data, options, responsiveOptions);
  });

});
