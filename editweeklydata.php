<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$test_id = mysqli_real_escape_string($dbc, trim($_GET['id']));

$page_title = 'Edit Weekly Data';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<script src="js/adddata_scripts.js"></script>';
echo '<script src="js/editdata_scripts.js"></script>';

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
        Edit Weekly Data
      </h1>
      <p class="lead">
        Use this page to document assessment data for a class or set of classes.
      </p>
      <p>
        After updating this form you will be able to print a data report with this data.
      </p>
      <form method="post" action="service.php">
        <input type="hidden" name="action" value="editWeeklyData">
				<input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
				<?php
				$query = "SELECT count(*) AS count FROM data_classes WHERE test_id = $test_id";
				$result = mysqli_query($dbc, $query);
				$classTotal = mysqli_fetch_array($result)['count'];
				?>
				<input type="hidden" name="classTotal" value="<?php echo $classTotal; ?>">
        <h2>
          Assessment Overview
        </h2>
				<?php
				$query = "SELECT teacher, week, grade, course, standards, test_name FROM data_tests WHERE test_id = $test_id";
				$result = mysqli_query($dbc, $query);
				$row = mysqli_fetch_array($result);
				?>
        <div class="form-group row">
          <label for="name" class="col-sm-3 col-form-label">Teacher Name</label>
          <div class="col-sm-9">
            <input type="text" name="name" class="form-control" id="name" value="<?php echo $row['teacher']; ?>">
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
									echo '<option value="' . date('Y-m-d', $i) . '"';
									if (date('Y-m-d', $i) == $row['week']) {
										echo ' selected';
									}
									echo '>Week ' . $j . ' (Starting ' . date('n/j/Y', $i) . ')</option>';
									$j++;
								}
              ?>
            </select>
						<small class="muted-text text-success required">Required</small>
          </div>
        </div>
				<div class="form-group row">
					<label for="grade" class="col-sm-3 col-form-label">Grade Level</label>
					<div class="col-sm-9">
						<select class="form-control grade-select" id="grade" name="grade">
							<option disabled selected></option>
							<?php
							for ($i = 3; $i < 9; $i++) {
								echo '<option value="' . $i . '"';
								if ($i == $row['grade']) {
									echo ' selected';
								}
								echo '>Grade ' . $i . '</option>';
							}
							?>
							<option value="High School" <?php if ($row['grade'] == 'High School') { echo 'selected'; } ?> >High School</option>
						</select>
						<small class="muted-text required text-success">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="course" class="col-sm-3 col-form-label">Course</label>
					<div class="col-sm-9">
						<select class="form-control course-select" id="course" name="course">
							<option disabled selected></option>
							<?php $other = 1; ?>
							<option value="ELA" <?php if ($row['course'] == 'ELA') { echo 'selected'; $other = 0; } ?>>English 3 - 8</option>
							<option value="Math" <?php if ($row['course'] == 'Math') { echo 'selected'; $other = 0; } ?>>Math 3 - 8</option>
							<option value="Sci" <?php if ($row['course'] == 'Sci') { echo 'selected'; $other = 0; } ?>>Science 5/8</option>
							<option value="Alg" <?php if ($row['course'] == 'Alg') { echo 'selected'; $other = 0; } ?>>Algebra I</option>
							<option value="Bio" <?php if ($row['course'] == 'Bio') { echo 'selected'; $other = 0; } ?>>Biology</option>
							<option value="Eng" <?php if ($row['course'] == 'Eng') { echo 'selected'; $other = 0; } ?>>English II</option>
							<option value="US Hist" <?php if ($row['course'] == 'US Hist') { echo 'selected'; $other = 0; } ?>>US History</option>
							<option value="Other" <?php if ($other == 1) { echo 'selected'; } ?>>Other</option>
						</select>
						<small class="muted-text required text-success">Required</small>
					</div>
				</div>
				<div class="form-group row other-row" <?php if ($other == 0) { echo 'style="display: none;"'; } ?>>
					<label for="other" class="col-sm-3 col-form-label">Course Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="other" name="other" maxlength="30" value="<?php echo $row['course']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="test" class="col-sm-3 col-form-label">Test Name</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="test" name="test" maxlength="30" value="<?php echo $row['test_name']; ?>">
						<small class="muted-text required text-success">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="standards" class="col-sm-3 col-form-label">Standards Assessed</label>
					<div class="col-sm-9">
						<input type="text" name="standards" class="form-control" id="standards" maxlength="40" value="<?php echo $row['standards']; ?>">
						<small class="muted-text text-success required">Required</small>
					</div>
				</div>
        <h2>
          Assessment Data
        </h2>
				<p>
					Complete information for each class.
				</p>
				<div id="classDiv">
					<?php
					$query = "SELECT * FROM data_classes WHERE test_id = $test_id ORDER BY row_id ASC";
					$result = mysqli_query($dbc, $query);
					while ($row = mysqli_fetch_array($result)) {
						?>
						<hr>
						<h3>
							Class 1
						</h3>
						<div class="alert alert-info">
							Use the same class name each week to get longitudinal data on the Weekly Data Report.
						</div>
						<div class="form-group row">
							<label for="class-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Class Name/Period</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" id="class-<?php echo $row['row_id']; ?>" name="class-<?php echo $row['row_id']; ?>" value="<?php echo $row['class']; ?>">
								<small class="muted-text required text-success">Required</small>
							</div>
						</div>
						<div class="form-group row">
		          <label for="average-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Class Average</label>
		          <div class="col-sm-9">
		            <input type="number" class="form-control" id="average-<?php echo $row['row_id']; ?>" name="average-<?php echo $row['row_id']; ?>" step="0.1" value="<?php echo $row['average']; ?>">
								<small class="muted-text text-success required">Required</small><br/>
								<small class="muted-text">Round to the nearest tenth (0.x).</small>
		          </div>
		        </div>
						<p>
							Use students' test scores to answer the following questions.
						</p>
						<div class="form-group row">
		          <label for="minimal-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Number 0% - 59%</label>
		          <div class="col-sm-9">
		            <input type="number" class="form-control" id="minimal-<?php echo $row['row_id']; ?>" name="minimal-<?php echo $row['row_id']; ?>" value="<?php echo $row['minimal']; ?>">
								<small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<div class="form-group row">
		          <label for="basic-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Number 60% - 69%</label>
		          <div class="col-sm-9">
		            <input type="number" class="form-control" id="basic-<?php echo $row['row_id']; ?>" name="basic-<?php echo $row['row_id']; ?>" value="<?php echo $row['basic']; ?>">
								<small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<div class="form-group row">
		          <label for="pass-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Number 70% - 79%</label>
		          <div class="col-sm-9">
		            <input type="number" class="form-control" id="pass-<?php echo $row['row_id']; ?>" name="pass-<?php echo $row['row_id']; ?>" value="<?php echo $row['pass']; ?>">
								<small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<div class="form-group row">
		          <label for="pro-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Number 80% - 94%</label>
		          <div class="col-sm-9">
		            <input type="number" class="form-control" id="pro-<?php echo $row['row_id']; ?>" name="pro-<?php echo $row['row_id']; ?>" value="<?php echo $row['pro']; ?>">
								<small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<div class="form-group row">
		          <label for="adv-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Number 95% - 100%</label>
		          <div class="col-sm-9">
		            <input type="number" class="form-control" id="adv-<?php echo $row['row_id']; ?>" name="adv-<?php echo $row['row_id']; ?>" value="<?php echo $row['adv']; ?>">
								<small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<div class="form-group row">
		          <label for="practice-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">What does this tell you about your practice?</label>
		          <div class="col-sm-9">
		            <textarea class="form-control" id="practice-<?php echo $row['row_id']; ?>" rows="3" name="practice-<?php echo $row['row_id']; ?>" maxlength="250"><?php echo $row['practice']; ?></textarea>
		            <small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
		        <div class="form-group row">
		          <label for="victories-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">Where are the victories?</label>
		          <div class="col-sm-9">
		            <textarea class="form-control" id="victories-<?php echo $row['row_id']; ?>" rows="3" name="victories-<?php echo $row['row_id']; ?>" maxlength="250"><?php echo $row['victories']; ?></textarea>
		            <small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<div class="form-group row">
		          <label for="better-<?php echo $row['row_id']; ?>" class="col-sm-3 col-form-label">How can you get better from this?</label>
		          <div class="col-sm-9">
		            <textarea class="form-control" id="better-<?php echo $row['row_id']; ?>" rows="3" name="better-<?php echo $row['row_id']; ?>" maxlength="250"><?php echo $row['better']; ?></textarea>
		            <small class="muted-text text-success required">Required</small>
		          </div>
		        </div>
						<hr>
						<?php
					}
					?>
				</div>

        <div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
        <a class="btn btn-danger" href="weeklydatacharts.php?id=<?php echo $test_id; ?>">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
