<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Weekly Data';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<script src="js/typeahead.js"></script>';
echo '<script src="js/adddata_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-classes.php');
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
    <p class="lead">
      Manage and view weekly data
    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Add Weekly Data
      </h1>
      <p class="lead">
        Use this page to document assessment data for a class or set of classes.
      </p>
      <p>
        After submitting this form you will be able to print a data report with this data.
      </p>
      <form method="post" action="service.php">
        <input type="hidden" name="action" value="addWeeklyData">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
				<input type="hidden" name="classTotal" value="1" id="classTotal">
        <h2>
          Assessment Overview
        </h2>
        <div class="form-group row">
          <label for="name" class="col-sm-3 col-form-label">Teacher Name</label>
          <div class="col-sm-9">
            <input type="text" name="name" class="form-control" id="name" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
            <small class="muted-text required text-success">Required</small>
          </div>
        </div>
				<div class="form-group row">
          <label for="week" class="col-sm-3 col-form-label">Week</label>
          <div class="col-sm-9">
            <select class="form-control" id="week" name="week">
							<option disabled selected></option>
              <?php
								$j = 1;
								$endDate = strtotime('now');
								$startDate = '2017-08-07';
								for($i = strtotime('Monday', strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i)) {
									echo '<option value="' . date('Y-m-d', $i) . '">Week ' . $j . ' (Starting ' . date('n/j/Y', $i) . ')</option>';
									$j++;
								}
              ?>
            </select>
						<small class="muted-text text-danger required">Required</small>
          </div>
        </div>
				<div class="form-group row">
					<label for="grade" class="col-sm-3 col-form-label">Grade Level</label>
					<div class="col-sm-9">
						<select class="form-control grade-select" id="grade" name="grade">
							<option disabled selected></option>
							<?php
							for ($i = 3; $i < 9; $i++) {
								echo '<option value="' . $i . '">Grade ' . $i . '</option>';
							}
							?>
							<option value="High School">High School</option>
						</select>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="course" class="col-sm-3 col-form-label">Course</label>
					<div class="col-sm-9">
						<select class="form-control course-select" id="course" name="course">
							<option disabled selected></option>
							<option value="ELA">English 3 - 8</option>
							<option value="Math">Math 3 - 8</option>
							<option value="Sci">Science 5/8</option>
							<option value="Alg">Algebra I</option>
							<option value="Bio">Biology</option>
							<option value="Eng">English II</option>
							<option value="US Hist">US History</option>
							<option value="Other">Other</option>
						</select>
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="form-group row other-row" style="display: none;">
					<label for="other" class="col-sm-3 col-form-label">Course Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="other" name="other" maxlength="30">
					</div>
				</div>
				<div class="form-group row">
					<label for="test" class="col-sm-3 col-form-label">Test Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="test" name="test" maxlength="30">
						<small class="muted-text required text-danger">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="standards" class="col-sm-3 col-form-label">Standards Assessed</label>
					<div class="col-sm-9">
						<input type="text" name="standards" class="form-control" id="standards" maxlength="40">
						<small class="muted-text text-danger required">Required</small>
					</div>
				</div>
        <h2>
          Assessment Data
        </h2>
				<p>
					Complete information for each class.
				</p>
				<div id="classDiv">
					<hr>
					<h3>
						Class 1
					</h3>
					<div class="alert alert-info">
						Use the same class name each week to get longitudinal data on the Weekly Data Report.
					</div>
					<div class="form-group row">
						<label for="class-1" class="col-sm-3 col-form-label">Class Name/Period</label>
						<div class="col-sm-9">
							<input type="text" class="form-control typeahead" id="class-1" name="class-1">
							<small class="muted-text required text-danger">Required</small>
						</div>
					</div>
					<div class="form-group row">
	          <label for="average-1" class="col-sm-3 col-form-label">Class Average</label>
	          <div class="col-sm-9">
	            <input type="number" class="form-control" id="average-1" name="average-1" step="0.1">
							<small class="muted-text text-danger required">Required</small><br/>
							<small class="muted-text">Round to the nearest tenth (0.x).</small>
	          </div>
	        </div>
					<p>
						Use students' test scores to answer the following questions.
					</p>
					<div class="form-group row">
	          <label for="minimal-1" class="col-sm-3 col-form-label">Number 0% - 64%</label>
	          <div class="col-sm-9">
	            <input type="number" class="form-control" id="minimal-1" name="minimal-1">
							<small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="basic-1" class="col-sm-3 col-form-label">Number 65% - 74%</label>
	          <div class="col-sm-9">
	            <input type="number" class="form-control" id="basic-1" name="basic-1">
							<small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="pass-1" class="col-sm-3 col-form-label">Number 75% - 84%</label>
	          <div class="col-sm-9">
	            <input type="number" class="form-control" id="pass-1" name="pass-1">
							<small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="pro-1" class="col-sm-3 col-form-label">Number 85% - 94%</label>
	          <div class="col-sm-9">
	            <input type="number" class="form-control" id="pro-1" name="pro-1">
							<small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="adv-1" class="col-sm-3 col-form-label">Number 95% - 100%</label>
	          <div class="col-sm-9">
	            <input type="number" class="form-control" id="adv-1" name="adv-1">
							<small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="practice-1" class="col-sm-3 col-form-label">What does this tell you about your practice?</label>
	          <div class="col-sm-9">
	            <textarea class="form-control" id="practice-1" rows="3" name="practice-1" maxlength="250"></textarea>
	            <small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
	        <div class="form-group row">
	          <label for="victories-1" class="col-sm-3 col-form-label">Where are the victories?</label>
	          <div class="col-sm-9">
	            <textarea class="form-control" id="victories-1" rows="3" name="victories-1" maxlength="250"></textarea>
	            <small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<div class="form-group row">
	          <label for="better-1" class="col-sm-3 col-form-label">How can you get better from this?</label>
	          <div class="col-sm-9">
	            <textarea class="form-control" id="better-1" rows="3" name="better-1" maxlength="250"></textarea>
	            <small class="muted-text text-danger required">Required</small>
	          </div>
	        </div>
					<hr>
				</div>
				<p>
				  <a class="btn btn-outline-primary" href="#!" id="addItem">Add Class</a>
				  <a class="btn btn-outline-danger disabled" href="#!" id="removeItem">Remove Class</a>
				</p>

        <div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" href="#!" id="btnSubmitForm">Submit</a>
        <a class="btn btn-danger" href="weeklydata.php">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
