<?php

$page_title = 'District Spotlight';
include('header_nosignin.php');

//include other scripts needed here
?>

<style>

.carousel-item {
  align-items: center;
  width: 100%;
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
			Welcome to Hollandale
		</h1>
    <p class="lead">
      "Average is Over"
    </p>
	</div>
</div>
<div class="container mt-2">
	<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner" role="listbox">
        <div class="carousel-item active">
          <img class="d-block mh-75 img-fluid" src="images/classroom_students.jpg" alt="First slide">
        </div>
        <div class="carousel-item">
          <img class="d-block img-fluid" src="http://jossan.home.xs4all.nl/fotos/andere_plaatsen/img/IMG_0439_1500x500v.jpg" alt="Second slide">
        </div>
        <div class="carousel-item">
          <img class="d-block img-fluid" src="http://jossan.home.xs4all.nl/fotos/rotterdam/img/IMG_9237_1500x500.jpg" alt="Third slide">
        </div>
      </div>
    </div>
</div>


<script>
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

</script>



<?php

//include footer
include('footer.php');
?>
