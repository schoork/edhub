<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Add Safety Incident';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<script src="js/addsafety_scripts.js"></script>';

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
			Add Safety Incident
			</h1>
			<p class="lead">
			Use this page to report a safety incident involving one or more students.
			</p>
			<p>
				<a class="btn btn-primary" href="safety.php">Safety Incidents</a>
			</p>
			<form method="post" action="service.php">
				<input type="hidden" name="action" value="addSafety">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
				<input type="hidden" name="school" value="<?php echo $_SESSION['school']; ?>">
				<input type="hidden" name="studentTotal" value="1" id="studentTotal">
				<h2>
					Student(s) Involved
				</h2>
				<div id="studentDiv">
					<div id="student_id_div">
						<div class="form-group row">
							<label for="student_id-1" class="col-sm-3 col-form-label">Student 1</label>
							<div class="col-sm-9">
								<select class="form-control" id="student_id-1" name="student_id-1">
									<option disabled selected></option>
									<?php
										$query = "SELECT firstname, lastname, grade, student_id FROM student_list ORDER BY lastname, firstname";
										$result = mysqli_query($dbc, $query);
										while ($row = mysqli_fetch_array($result)) {
											echo '<option value="' . $row['student_id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . ' (Gr.' . $row['grade'] . ')</option>';
										}
									?>
								</select>
								<small class="muted-text text-danger required">Required</small>
							</div>
						</div>
					</div>
				</div>
				<p>
					<a class="btn btn-outline-primary" href="#!" id="addItem">Add Student</a>
					<a class="btn btn-outline-danger disabled" href="#!" id="removeItem">Remove Student</a>
				</p>
				<div class="form-group row">
					<label class="col-sm-3">Location</label>
						<div class="col-sm-9">
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="Classroom">
								Classroom
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="Hallway">
								Hallway
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="Gym">
								Gym
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="Playground/Field">
								Playground/Field
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="School Bus">
								School Bus
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="Other on Campus Location">
								Other on Campus Location
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="location" value="Off Campus">
								Off Campus
							</label>
						</div>
						<small class="muted-text text-danger required">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3">Was a staff member at the scene of the incident?</label>
					<div class="col-sm-9">
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="staff_present" value="Yes">
								Yes
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" name="staff_present" value="No">
								No
							</label>
						</div>
						<small class="muted-text text-danger required">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="staff_name" class="col-sm-3 col-form-label">Staff Present</label>
					<div class="col-sm-9">
						<input type="text" name="staff_name" class="form-control" id="staff_name" maxlength="100">
					</div>
				</div>
				<div class="form-group row">
					<label for="description" class="col-sm-3 col-form-label">Describe the incident</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="description" rows="5" name="description" maxlength="500"></textarea>
						<small class="muted-text text-danger required">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label for="injuries" class="col-sm-3 col-form-label">Describe any injuries to the student</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="injuries" rows="5" name="injuries" maxlength="500"></textarea>
						<small class="muted-text text-danger required">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3">Immediate Action(s) Taken (check all that apply)</label>
					<div class="col-sm-9">
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="actions_taken[]" value="Student Isolated">
								Student Isolated
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="actions_taken[]" value="First Aid">
								First Aid
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="actions_taken[]" value="CPR">
								CPR
							</label>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<label for="actions_taken_desc" class="col-sm-3 col-form-label">Describe any other actions taken</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="actions_taken_desc" rows="5" name="actions_taken_desc" maxlength="250"></textarea>
						<small class="muted-text text-danger required">Required</small>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-3">People Contacted</label>
					<div class="col-sm-9">
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="contacts[]" value="Parent/Guardian">
								Parent/Guardian
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="contacts[]" value="Nurse">
								Nurse
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="contacts[]" value="SRO">
								SRO
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="contacts[]" value="Superintendent/Designee">
								Superintendent/Designee
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="contacts[]" value="Paramedic">
								Paramedic*
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="contacts[]" value="Law Enforcement">
								Law Enforcement*
							</label>
						</div>
						<small class="muted-text text-danger required">Required</small><br>
						<small class="muted-text">*Only the Nurse, a principal, the Superintendent, or SRO should call a paramedic or law enforcement</small>
					</div>
				</div>
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
