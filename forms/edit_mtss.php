<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Edit Student MTSS Data';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<script src="js/editmtss_scripts.js"></script>';
echo '<link rel="stylesheet" href="forms/reimbursement_styles.css">';

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
			Students
		</h1>
    <p class="lead">
      Manage students and interventions
    </p>
	</div>
</div>
<?php
if (isset($_POST['action'])) {
	$student_id = mysqli_real_escape_string($dbc, trim($_POST['student_id']));
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$flags = $_POST['flags'];
	$months = array('August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May');
	$refs = mysqli_real_escape_string($dbc, trim($_POST['refs']));
	$iss = mysqli_real_escape_string($dbc, trim($_POST['iss']));
	$oss = mysqli_real_escape_string($dbc, trim($_POST['oss']));
	$iss_days = mysqli_real_escape_string($dbc, trim($_POST['iss_days']));
	$oss_days = mysqli_real_escape_string($dbc, trim($_POST['oss_days']));
	$ela_pl = mysqli_real_escape_string($dbc, trim($_POST['ela_pl']));
	$math_pl = mysqli_real_escape_string($dbc, trim($_POST['math_pl']));

	//main info
	$query = "UPDATE student_interventions SET refs = $refs, iss = $iss, oss = $oss, iss_days = $iss_days, oss_days = $oss_days, flags = '" . implode($flags, ", ") . "', ela_pl = '$ela_pl', math_pl = '$math_pl' WHERE student_id = $student_id";
	mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));

	//attendance info
	$query = "DELETE FROM interventions_attendance WHERE student_id = $student_id";
	mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
	foreach ($months as $month) {
		if (!empty($_POST[$month]) || $_POST[$month] != 0) {
			$abs = mysqli_real_escape_string($dbc, trim($_POST[$month]));
			$query = "INSERT INTO interventions_attendance (student_id, month, absences) VALUES ($student_id, '$month', $abs)";
			mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
		}
	}

	//interventions_scores
	if ($_POST['testNum'] > 0) {
		for ($i = 1; $i <= $_POST['testNum']; $i++) {
			$date = mysqli_real_escape_string($dbc, trim($_POST["date-$i"]));
			$type = mysqli_real_escape_string($dbc, trim($_POST["type-$i"]));
			$score = mysqli_real_escape_string($dbc, trim($_POST["score-$i"]));
			$query = "INSERT INTO interventions_scores (student_id, date, score, type) VALUES ($student_id, '$date', '$type', $score)";
			mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
		}
	}

	//behavior plan
	$file_tmp = $_FILES["file-$i"]['tmp_name'];
	$file_name = $_FILES["file-$i"]['name'];
	if ($file_tmp != '') {
		$target_dir = "plans/";
		$target_file = $target_dir . basename($file_name);
		if (!move_uploaded_file($file_tmp,"plans/".$file_name)) {
			echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem uploading your files. Click the button below to try again. If the problem persists, contact Mr. Williams. (Error Code 2.1)</div><p><a class="btn btn-primary" href="addform.php">Retry</a></p>';
		}
		else {
			$query = "UPDATE student_interventions SET beh_plan = '$target_file' WHERE student_id = $student_id";
			mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
		}
	}
}
else {
	$student_id = mysqli_real_escape_string($dbc, trim($_GET['id']));
}
echo '<input type="hidden" id="student_id" value="' . $student_id . '">';
$query = "SELECT firstname, lastname, grade, flags, refs, iss, oss, beh_plan, iss_days, oss_days, ela_pl, math_pl FROM student_interventions WHERE student_id = $student_id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

?>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Edit Student MTSS Data - <?php echo $row['firstname'] . ' ' . $row['lastname'] . ' (Gr ' . $row['grade'] . ')'; ?>
      </h1>
      <form method="post" action="edit_mtss.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="editMtssData">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
				<input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
				<div class="form-group row">
				  <label class="col-sm-3">MTSS Flags</label>
				  <div class="col-sm-9">
				    <div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="flags[]" value="A" <?php if (strpos($row['flags'], 'A') !== false) { echo 'checked="checked"'; }?>>
				        Attendance
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="flags[]" value="B" <?php if (strpos($row['flags'], 'B') !== false) { echo 'checked="checked"'; }?>>
				        Behavior
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="flags[]" value="CG" <?php if (strpos($row['flags'], 'CG') !== false) { echo 'checked="checked"'; }?>>
				        Grades
				      </label>
				    </div>
						<div class="form-check">
				      <label class="form-check-label">
				        <input type="checkbox" class="form-check-input" name="flags[]" value="LQ" <?php if (strpos($row['flags'], 'LQ') !== false) { echo 'checked="checked"'; }?>>
				        Low Quartile
				      </label>
				    </div>
					</div>
				</div>
				<div class="form-group row">
					<label for="ela_pl" class="col-sm-3 col-form-label">ELA Performance Level</label>
					<div class="col-sm-9">
						<input type="text" name="ela_pl" class="form-control" id="ela_pl" value="<?php echo $row['ela_pl']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="math_pl" class="col-sm-3 col-form-label">Math Performance Level</label>
					<div class="col-sm-9">
						<input type="text" name="math_pl" class="form-control" id="math_pl" value="<?php echo $row['math_pl']; ?>">
					</div>
				</div>
				<h2>
					Absences
				</h2>
				<?php
				$query = "SELECT month, absences FROM interventions_attendance WHERE student_id = $student_id";
				$result = mysqli_query($dbc, $query);
				$absences = array();
				if (mysqli_num_rows($result) > 0) {
					while ($line = mysqli_fetch_array($result)) {
						$absences[$line['month']] = $line['absences'];
					}
				}
				$months = array('August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May');
				foreach ($months as $month) {
					echo '<div class="form-group row">';
					echo '<label for="' . $month . '" class="col-sm-3 col-form-label">' . $month . '</label>';
					echo '<div class="col-sm-9">';
					echo '<input type="number" name="' . $month . '" class="form-control" id="' . $month . '" value="' . $absences[$month] . '">';
					echo '</div></div>';
				}
				?>
				<h2>
					Behavior
				</h2>
				<div class="form-group row">
					<label for="refs" class="col-sm-3 col-form-label">Number of Referrals</label>
					<div class="col-sm-9">
						<input type="number" name="refs" class="form-control" id="refs" value="<?php echo $row['refs']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="iss" class="col-sm-3 col-form-label">Number of ISS Referrals</label>
					<div class="col-sm-9">
						<input type="number" name="iss" class="form-control" id="iss" value="<?php echo $row['iss']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="iss_days" class="col-sm-3 col-form-label">Number of Days in ISS</label>
					<div class="col-sm-9">
						<input type="number" name="iss_days" class="form-control" id="iss_days" value="<?php echo $row['iss_days']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="oss" class="col-sm-3 col-form-label">Number of OSS Referrals</label>
					<div class="col-sm-9">
						<input type="number" name="oss" class="form-control" id="oss" value="<?php echo $row['oss']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<label for="oss_days" class="col-sm-3 col-form-label">Number of Days in OSS</label>
					<div class="col-sm-9">
						<input type="number" name="oss_days" class="form-control" id="oss_days" value="<?php echo $row['oss_days']; ?>">
					</div>
				</div>
				<div class="form-group row">
					<div class="col-sm-3">Behavior Plan</div>
				  <div class="col-sm-9">
				    <label class="custom-file">
				      <input type="file" id="file-1" name="file-1" class="custom-file-input">
				      <span class="custom-file-control"></span>
				    </label>
				  </div>
				</div>
				<h2>
					Test Scores
				</h2>
				<input type="hidden" name="testNum" value="0" id="testNum">
				<div id="testDiv">

				</div>
				<p>
				  <a class="btn btn-outline-primary" href="#!" id="addItem">Add Test Score</a>
				  <a class="btn btn-outline-danger disabled" href="#!" id="removeItem">Remove Test Score</a>
				</p>
				<div class="form-group row">
				  <label for="notes" class="col-sm-3 col-form-label">Notes</label>
				  <div class="col-sm-9">
				    <textarea class="form-control" id="notes" rows="5" name="notes" maxlength="200"></textarea>
				  </div>
				</div>


        <div id="alert" class="alert" role="alert">

        </div>
        <button class="btn btn-primary" id="btnSubmit" disabled>Submit</button>
        <a class="btn btn-danger" href="interventions.php?id=<?php echo $student_id; ?>">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
