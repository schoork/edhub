<?php
$page_title = 'Weekly Data';
$page_access = 'All';
include('header.php');

echo '<link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">';
echo '<script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>';
echo '<script src="js/plugin-legend.js"></script>';
echo '<link rel="stylesheet" href="css/plugin-legend-bootstrap.css">';
echo '<script src="js/viewdata_scripts.js"></script>';
echo '<link rel="stylesheet" href="css/viewdata_styles.css">';

//include other scripts needed here

//end header
echo '</head>';

include('navbar-classes.php');

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
?>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<div class="bd-pageheader bg-primary text-white pt-4 pb-4 d-print-none">
	<div class="container">
		<h1>
			Classes
		</h1>
    <p class="lead">
      Manage and submit lesson plans, weekly assessment data, and inventory
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
      <h1>
        Weekly Data
      </h1>
      <p>
        <a class="btn btn-primary" href="upload_data.php">Upload Data</a>
				<a class="btn btn-success" href="#!" style="display: none;" id="printBtn">Print Data</a>
      </p>
			<div class="form-group row d-print-none">
        <div class="col">
          <select class="form-control" id="week">
            <option disabled selected>Select a week</option>
            <?php
            $query = "SELECT week FROM data_students GROUP BY week ORDER BY week";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)) {
              echo '<option value="' . $row['week'] . '">' . makeDateAmerican($row['week']) . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
			<div class="row">
        <div class="col">
          <div class="ct-chart ct-major-eleventh wide-stroke" id="chart-week"></div>
        </div>
      </div>
      <div class="form-group row">
        <div class="col">
          <select class="form-control" id="subject">
            <option disabled selected>Select a subject</option>
            <?php
            $query = "SELECT subject FROM data_teachers GROUP BY subject ORDER BY subject";
            $result = mysqli_query($dbc, $query);
            while ($row = mysqli_fetch_array($result)) {
              echo '<option value="' . $row['subject'] . '">' . $row['subject'] . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="ct-chart ct-major-eleventh wide-stroke" id="chart-subject"></div>
        </div>
      </div>
			<div class="row">
				<div class="col">
					<table class="table table-sm table-bordered text-center" id="table-subject">
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<table class="table table-sm table-bordered text-center" id="table-subject2">
					</table>
				</div>
			</div>
			<input type="hidden" id="test_ids">
      <div class="form-group row">
        <div class="col">
          <select class="form-control" id="teacher">
            <option disabled selected>Select a teacher</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="ct-chart ct-major-eleventh wide-stroke" id="chart-teacher"></div>
        </div>
      </div>
			<div class="row">
				<div class="col">
					<table class="table table-sm table-bordered text-center" id="table-teacher">
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<table class="table table-sm table-bordered text-center" id="table-teacher2">
					</table>
				</div>
			</div>
      <div class="form-group row">
        <div class="col">
          <select class="form-control" id="period">
            <option disabled selected>Select a period</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="ct-chart ct-major-eleventh mb-3" id="chart-period"></div>
        </div>
      </div>
			<input type="hidden" id="course">
      <div class="form-group row">
        <div class="col">
          <select class="form-control" id="student">
            <option disabled selected>Select a student</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="ct-chart ct-major-eleventh" id="chart-student"></div>
        </div>
      </div>


    </div>
  </div>
</div>
</body>
<?php

mysqli_close($dbc);

?>
</html>
