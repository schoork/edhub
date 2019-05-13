<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Add Intervention';
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
include('navbar-students.php');
 
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
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Add Intervention
      </h1>
      <form method="post" action="service.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="addIntervention">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
				<input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
				<div class="form-group row">
					<label for="student_id" class="col-sm-3">Student</label>
					<div class="col-sm-9">
						<select id="student_id" name="student_id" class="form-control">
							<option disabled selected></option>
							<?php
							$query = "SELECT student_id, firstname, lastname FROM student_interventions ORDER BY lastname, firstname";
							$result = mysqli_query($dbc, $query);
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="' . $row['student_id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
							}
							?>
						</select>
						<small class="required muted-text text-danger">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="intervention" class="col-sm-3">Intervention</label>
					<div class="col-sm-9">
						<select id="intervention" name="intervention" class="form-control">
							<option disabled selected></option>
							<option value="Behavior Contract">Behavior Contract</option>
							<option value="Call Home">Call Home</option>
							<option value="Check In/Check out">Check In/Check out</option>
							<option value="Classroom job">Classroom job</option>
							<option value="Daily Behavior Rating">Daily Behavior Rating</option>
							<option value="Peer tutoring">Peer tutoring</option>
							<option value="Individualized reward system">Individualized reward system</option>
							<option value="Self-monitoring tool">Self-monitoring tool</option>
							<option value="Structured breaks/Classroom Breaks">Structured breaks/Classroom Breaks</option>
						</select>
						<small class="required muted-text text-danger">Required</small><br>
						<small class="muted-text">If you have an intervention that you think should be added to the list, please email <a href="mailto:swilliams@hollandalesd.org">Sam Williams</a>.</small>
					</div>
				</div>
				<div class="form-group row">
				  <label for="notes" class="col-sm-3 col-form-label">Notes</label>
				  <div class="col-sm-9">
				    <textarea class="form-control" id="notes" rows="5" name="notes" maxlength="200"></textarea>
				  </div>
				</div>


        <div id="alert" class="alert" role="alert">

        </div>
        <a class="btn btn-primary" id="btnSubmitForm" href="#!">Submit</a>
        <a class="btn btn-danger" href="interventions.php">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
