<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Schedule Meeting';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';

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
				Schedule Meeting
			</h1>
			<p class="lead">
				Use this page to schedule a student-based meeting.
			</p>
			<p>
				<a class="btn btn-secondary" href="meetings.php">Meetings</a>
			</p>
			<form method="post" action="service.php">
				<input type="hidden" name="action" value="scheduleMeeting">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                <input type="hidden" name="user" value="<?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="student_id">Student</label>
		            	<select class="form-control" id="student_id" name="student_id">
							<option disabled selected></option>
		              		<?php
							$query = "SELECT firstname, lastname, grade, student_id FROM student_list ORDER BY lastname, firstname";
							$result = mysqli_query($dbc, $query);
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="' . $row['student_id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . ' (Gr. ' . $row['grade'] . ')</option>';
							}
		              		?>
		            	</select>
						<small class="muted-text text-danger required">Required</small>
		          	</div>
                    <div class="form-group col-md-6">
                        <label for="type">Meeting Type</label>
		            	<select class="form-control" id="type" name="type">
							<option disabled selected></option>
                            <option value="IEP Meeting">IEP Meeting</option>
                            <option value="Manifestation Meeting">Manifestation Meeting</option>
                            <option value="Disciplinary Hearing">Disciplinary Hearing</option>
		            	</select>
						<small class="muted-text text-danger required">Required</small>
		          	</div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date">Date of Meeting</label>
                        <input type="date" class="form-control" name="date" id="date">
                        <small class="muted-text text-danger required">Required</small><br>
                        <small class="muted-text">mm/dd/yyyy</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="time">Time of Meeting</label>
                        <input type="time" class="form-control" name="time" id="time">
                        <small class="muted-text text-danger required">Required</small><br>
                        <small class="muted-text">hh:mm AM/PM</small>
                    </div>
		        </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="notes">Description/Notes</label>
                        <textarea rows="5" class="form-control" name="notes" id="notes"></textarea>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="staff">Staff</label>
		            	<select class="form-control" id="staff" name="staff[]" multiple size="5">
							<option disabled selected></option>
		              		<?php
                            $username = $_SESSION['username'];
							$query = "SELECT firstname, lastname, username FROM staff_list WHERE lastname NOT LIKE 'Vacancy%' AND status = 1 AND username <> '$username' ORDER BY lastname, firstname";
							$result = mysqli_query($dbc, $query);
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="' . $row['username'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . '</option>';
							}
		              		?>
		            	</select>
						<small class="muted-text text-danger required">Required</small><br>
                        <small class="muted-text">Hold Ctrl or ⌘ to select multiple options.</small>
                    </div>
                </div>
				<div id="alert" class="alert" role="alert">

				</div>
				<a class="btn btn-primary" href="#!" id="btnSubmitForm">Submit</a>
				<a class="btn btn-danger" href="meetings.php">Cancel</a>
			</form>
		</div>
	</div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
