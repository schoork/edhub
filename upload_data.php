<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Upload Data Files';
$page_access = 'Superintendent Admin Dept Head';
include('header.php');


//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

$courses = array(
	'3rd Grade ELA',
	'3rd Grade Math',
	'4th Grade ELA',
	'4th Grade Math',
	'5th Grade ELA',
	'5th Grade Math',
	'5th Grade Science',
	'6th Grade ELA',
	'6th Grade Math',
	'7th Grade ELA',
	'7th Grade Math',
	'8th Grade ELA',
	'8th Grade Math',
	'8th Grade Science',
	'Algebra I',
	'Biology I',
	'English II',
	'US History',
);
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
	    <p class="lead">
	    	Manage and submit lesson plans, weekly assessment data, and inventory
	    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
			<h1>Upload Data Files</h1>
			<?php
			if (!empty($_POST)) {
				$course = mysqli_real_escape_string($dbc, $_POST['course']);
				$week = mysqli_real_escape_string($dbc, $_POST['week']);
				$data = array();
				//open and read pre-test data file
				$myfile = fopen($_FILES['pre']['tmp_name'], "r");
				if ($myfile) {
					while (($line = fgets($myfile)) !== false) {
						$parts = preg_split('/[\t]/', $line);
						if ($parts[1] != 'StudentID') {
							$data[$parts[1]]['pre'] = $parts[6];
							$data[$parts[1]]['id'] = $parts[1];
						}
					}
					fclose($myfile);
				}
				else {
					echo '<div class="alert alert-danger">There was an error opening your pre-test data file.</div>';
				}
				//open and read post-test data file
				$myfile = fopen($_FILES['post']['tmp_name'], "r");
				if ($myfile) {
					while (($line = fgets($myfile)) !== false) {
						$parts = preg_split('/[\t]/', $line);
						if ($parts[1] != 'StudentID') {
							$data[$parts[1]]['post'] = $parts[6];
							$data[$parts[1]]['id'] = $parts[1];
						}
					}
					fclose($myfile);
				}
				else {
					echo '<div class="alert alert-danger">There was an error opening your post-test data file.</div>';
				}
				foreach ($data as $student) {
					if (isset($student['pre']) && isset($student['post'])) {
						$data[$student['id']]['growth'] = $student['post'] - $student['pre'];
					}
				}
				$newfile = fopen('scores.csv', 'w');
				foreach ($data as $student) {
					$line = array($student['id'], $course, $week, $student['pre'], $student['post'], $student['growth']);
					fputcsv($newfile, $line);
				}
				fclose($newfile);
				$query = "LOAD DATA LOCAL INFILE 'scores.csv' REPLACE INTO TABLE data_scores FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' (eadms_id, course, week, @pre, @post, @growth) SET pre = nullif(@pre, ''), post = nullif(@post, ''), growth = nullif(@growth, '')";
				if (mysqli_query($dbc, $query)) {
				  	echo '<div class="alert alert-success">Your data has been successfully uploaded!</div>';
				}
				else {
				  	echo '<div class="alert alert-danger">There was an error uploading your data. ' . mysqli_error($dbc) . '</div>';
				}
			}
			?>
			<form method="post" action="upload_data.php" enctype="multipart/form-data">
				<div class="form-row">
					<div class="form-group col-md-6">
	                    <label for="students">Course</label>
	                    <select class="form-control" id="course" name="course">
	                        <option disabled selected></option>
	                        <?php foreach ($courses as $course) {
								echo '<option value="'. $course . '">' . $course . '</option>';
							}
							?>
	                    </select>
	                </div>
					<div class="form-group col-md-6">
						<label for="week">Week</label>
						<select class="form-control" id="week" name="week">
							<option disabled selected></option>
							<?php
							$j = 1;
							$endDate = strtotime('now');
							$startDate = '2018-02-16';
							for($i = strtotime('Friday', strtotime($startDate)); $i <= $endDate; $i = strtotime('+1 week', $i)) {
								echo '<option value="' . date('Y-m-d', $i) . '">Week ' . $j . ' (Starting ' . date('n/j/Y', $i) . ')</option>';
								$j++;
							}
							?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="pre" name="pre">
							<label class="custom-file-label" for="pre">Choose Pre-Test...</label>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="post" name="post">
							<label class="custom-file-label" for="pre">Choose Post-Test...</label>
						</div>
					</div>
				</div>
				<p>
					<button type="submit" class="btn btn-primary">Submit</button>
			        <a class="btn btn-danger" href="weeklydata_courses.php">Cancel</a>
				</p>
			</form>
        </div>
	</div>
</div>

<script>
	$(document).on("change", "input[type='file']", function() {
		var fieldVal = $(this).val();
		fieldVal = fieldVal.substr(12);
		if (fieldVal != undefined || fieldVal != "") {
			fieldVal = fieldVal.substr(0);
			$(this).next(".custom-file-label").text(fieldVal);
		}
	});
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
