<?php

$page_title = 'Department Report';
$page_access = 'Principal Dept Head Admin Superintendent';
include('header.php');

//include other scripts needed here
echo '<script src="js/deptreport_scripts.js"></script>';
echo '<script src="js/forms_scripts.js"></script>';

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-district.php');
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			District
		</h1>
    <p class="lead">
      Manage forms and reports
    </p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
      <h1>
        Add Department Report
      </h1>
      <p class="lead">
        Use this page to submit a department report.
      </p>
			<p>
				<a href="weeklydeptreport.php" class="btn-outline-primary btn">View Weekly Dept Report</a>
			</p>
      <form method="post" action="service.php" id="deptForm">
        <input type="hidden" name="action" value="addDeptReport">
        <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
        <input type="hidden" name="upcomingNum" value="1" id="upcomingNum">
        <input type="hidden" name="webNum" value="1" id="webNum">
        <div class="form-group row">
          <label for="department" class="col-sm-3 col-form-label">Department</label>
          <div class="col-sm-9">
            <select class="form-control" id="department" name="department">
              <option disabled selected></option>
              <?php
              include('forms/departments.php');
              sort($depts);
              foreach ($depts as $dept) {
                echo '<option value="' . $dept . '">' . ucwords(str_replace("_", " ", $dept)) . '</option>';
              }
              ?>
            </select>
						<small class="muted-text required text-danger">Required</small>
          </div>
        </div>
        <h2>
          Upcoming Events
        </h2>
        <div id="old-upcoming" class="alert alert-info" style="display: none;">

        </div>
        <div id="upcomingDiv">
          <div class="form-group row">
            <label for="type-1" class="col-sm-3 col-form-label">Type</label>
            <div class="col-sm-9">
              <select class="form-control" id="type-1" name="type-1">
                <option disabled selected></option>
                <option value="Activity Trip">Activity Trip</option>
                <option value="Deadline">Deadline</option>
                <option value="PD or Training Event">PD or Training Event</option>
                <option value="Upcoming Event">Upcoming Event</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="title-1" class="col-sm-3 col-form-label" maxlength="160">Title</label>
            <div class="col-sm-9">
              <input type="text" name="title-1" class="form-control" id="title-1">
            </div>
          </div>
          <div class="form-group row">
            <label for="start-1" class="col-sm-3 col-form-label">Dates</label>
            <div class="col-sm-4">
              <input type="date" name="start-1" class="form-control" id="start-1" placeholder="From">
              <small class="muted-text">From</small><br/>
            </div>
            <div class="col-sm-4">
              <input type="date" name="end-1" class="form-control" id="end-1" placeholder="To">
              <small class="muted-text">To</small><br/>
            </div>
          </div>
					<div class="form-group row">
            <label for="time-1" class="col-sm-3 col-form-label">Time(s)</label>
            <div class="col-sm-9">
              <input type="text" name="time-1" class="form-control" id="time-1">
            </div>
          </div>
          <div class="form-group row">
            <label for="location-1" class="col-sm-3 col-form-label">Location</label>
            <div class="col-sm-9">
              <input type="text" name="location-1" class="form-control" id="location-1">
            </div>
          </div>
        </div>
        <p>
          <a class="btn btn-outline-primary addButton" href="#!" id="add-upcoming">Add</a>
        </p>
        <div class="alert alert-warning" role="alert">
          Please ensure you put all dates, trips, deadlines, etc. listed above on the appropriate Google Calendar.
        </div>
        <h2>
          Resources and Web Links
        </h2>
				<div id="old-web" class="alert alert-info" style="display: none;">

        </div>
        <div id="webDiv">
          <div class="form-group row">
            <label for="webtitle-1" class="col-sm-3 col-form-label">Title</label>
            <div class="col-sm-9">
              <input type="text" name="webtitle-1" class="form-control" id="webtitle-1">
            </div>
          </div>
          <div class="form-group row">
            <label for="url-1" class="col-sm-3 col-form-label">URL</label>
            <div class="col-sm-9">
              <input type="text" name="url-1" class="form-control" id="url-1">
            </div>
          </div>
          <div class="form-group row">
            <label for="description-1" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
              <textarea class="form-control" id="description-1" name="description-1" rows="6" maxlength="500"></textarea>
            </div>
					</div>
        </div>
        <p>
          <a class="btn btn-outline-primary addButton" href="#!" id="add-web">Add</a>
        </p>
        <h2>
          Other Important Information
        </h2>
        <div class="form-group row">
          <div class="col-sm-12">
            <textarea class="form-control" id="information" name="information" rows="6" maxlength="1000"></textarea>
          </div>
        </div>
        <div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
        <a class="btn btn-danger" href="weeklydeptreport.php">Cancel</a>
      </form>

    </div>
  </div>
</div>

<?php

//include footer
include('footer.php');
?>
