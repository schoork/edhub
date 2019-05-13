<?php

$page_title = 'District Spotlight';
include('header_nosignin.php');

//include other scripts needed here
echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
echo '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
echo '<script src="../js/moment.js"></script>';
echo '<script src="js/plugin-pointLabel.js"></script>';
echo '<script src="js/plugin-chartaccessibility.js"></script>';
echo '<script src="js/plugin-legend.js"></script>';

?>

<style>

#logo_div {
  position: absolute;
  left: 100px;
  bottom: 100px;
}

.carousel-item {
  align-items: center;
  width: 100%;
}
.carousel-item.active {
  display: block;
}
ul.events > li {
	font-size: 175%;
  list-style: none;
}
.ct-chart {
   position: relative;
}
.ct-legend {
   position: relative;
   z-index: 10;
   list-style: none;
   text-align: center;
}
.ct-legend li {
   position: relative;
   padding-left: 23px;
   margin-right: 10px;
   margin-bottom: 3px;
   cursor: pointer;
   display: inline-block;
}
.ct-legend li:before {
   width: 12px;
   height: 12px;
   position: absolute;
   left: 0;
   content: '';
   border: 3px solid transparent;
   border-radius: 2px;
   font-size: 120%;
}
.ct-legend li.inactive:before {
   background: transparent;
}
.ct-legend.ct-legend-inside {
   position: absolute;
   top: 0;
   right: 0;
}
.ct-legend.ct-legend-inside li{
   display: block;
   margin: 0;
}
.ct-legend .ct-series-0:before {
   background-color: #28a745;
   border-color: #28a745;
}
.ct-legend .ct-series-1:before {
   background-color: #007bff;
   border-color: #007bff;
}
.ct-legend .ct-series-2:before {
   background-color: #dc3545;
   border-color: #dc3545;
}
.ct-legend .ct-series-3:before {
   background-color: #ffc107;
   border-color: #ffc107;
}
.ct-legend .ct-series-4:before {
   background-color: #453d3f;
   border-color: #453d3f;
}
.ct-series-a .ct-line,
.ct-series-a .ct-point {
  stroke: #28a745;/* Control the thikness of your lines */
}
.ct-series-b .ct-line,
.ct-series-b .ct-point {
  stroke: #007bff;
}
.ct-series-c .ct-line,
.ct-series-c .ct-point {
  stroke: #dc3545;
}
.ct-series-d .ct-line,
.ct-series-d .ct-point {
  stroke: #ffc107;
}
.ct-series-a .ct-line,
.ct-series-b .ct-line,
.ct-series-c .ct-line,
.ct-series-d .ct-line {
  stroke-width: 10px;
  /* Create a dashed line with a pattern */
  stroke-dasharray: 10px 5px;
}
.ct-series-a .ct-point,
.ct-series-b .ct-point,
.ct-series-c .ct-point,
.ct-series-d .ct-point {
  stroke-width: 25px;
}


</style>

<?php

//end header
echo '</head>';

//start body
echo '<body>';
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Hollandale School District
		</h1>
    <p class="lead">
      The expectation is great because average is over
    </p>
	</div>
</div>
<div class="container mt-2">
	<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner" role="listbox">
        <div class="carousel-item">
          <img class="d-block mh-75 img-fluid" src="images/classroom_students.jpg" alt="First slide">
					<div class="carousel-caption text-dark">
				    <h3>
              Students participating in Ms. Lake's class
            </h3>
				  </div>
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="images/board.png">
        </div>
        <div class="carousel-item text-center p-4 text-primary">
          <h2>Upcoming Events</h2>
          <p>
            <ul class="events">
              <li>Walking for a Cure, Oct 11 at 8:00am</li>
              <li>DHA Consent Forms Due, Oct 11</li>
              <li>Fall Break, Oct 12 - 14</li>
              <li>Football @ Coffeeville, Oct 13 at 7:00pm</li>
              <li>Homecoming Week, Oct 16 - 20</li>
              <li>Red Ribbon Week, Oct 16 - 20</li>
              <li>Parent Conferences and Report Cards, Oct 18 at 4:30pm</li>
              <li>Homecoming Parade, Oct 20 at 4:00pm</li>
              <li>Football vs. South Delta (Homecoming), Oct 20 at 7:00pm</li>
            </ul>
          </p>
        </div>
        <div class="carousel-item">
          <img class="d-block w-100" src="images/donations.png">
        </div>
        <div class="carousel-item active">
          <h3>Weekly Test Data</h3>
          <div class="ct-chart ct-minor-sixth mt-3" id="chart1"></div>
        </div>
      </div>
    </div>
</div>

<div id="logo_div">
  <img src="images/hsd_logo.png">
</div>


<script>
$('.carousel').carousel({
  interval: 8000
});

var data1 = [], data2 = [], data3 = [], data4 = [];

$.getJSON("service.php?action=getSchoolLevelChartData", function(json) {
	$.each(json.schools, function() {
		if (this.school == 'Sanders') {
			data1[data1.length] = {x: new Date(this.week), y: this.average};
			data2[data2.length] = {x: new Date(this.week), y: this.prof};
		}
		else {
			data3[data3.length] = {x: new Date(this.week), y: this.average};
			data4[data4.length] = {x: new Date(this.week), y: this.prof};
		}
    buildWeeklyDataChart();
	});
});

function buildWeeklyDataChart() {
  var chart = new Chartist.Line('#chart1', {
		series: [
			{ "name": "Sanders Average", "data": data1},
			{ "name": "Sanders Proficiency", "data": data2},
			{ "name": "Simmons Average", "data": data3},
			{ "name": "Simmons Proficiency", "data": data4}
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
}

//Reload the page every 4 hours
setInterval(function(){
  window.refresh();
},14400000);

</script>



<?php

//include footer
include('footer.php');
?>
