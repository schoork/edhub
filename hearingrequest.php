<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Disciplinary Hearing Request';
$page_access = 'Principal Admin Superintendent Dept Head Counselor';
include('header.php');

//include other scripts needed here


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
                Disciplinary Hearing Request
            </h1>
            <p class="lead">
                Use this page to request a disciplinary hearing for a student.
            </p>
            <p>
                A disciplinary hearing is required due process for any student being expelled from school, recommended for alternative school placement, and/or suspended for a period longer than ten (10) days. A parent/guardian can waive his/her child's right to a disciplinary hearing using <a href="waiverform.pdf" target="_blank">this form</a>.
            </p>
            <p>
                <a class="btn btn-secondary" href="hearings.php">Hearings</a>
            </p>
            <?php
            if (!empty($_POST)) {
                $username = $_SESSION['username'];
                $student_id = $_POST['student_id'];
                $behavior = mysqli_real_escape_string($dbc, $_POST['behavior']);
                $parent = mysqli_real_escape_string($dbc, $_POST['parent']);
                $phone = mysqli_real_escape_string($dbc, $_POST['phone']);
                $incident_date = mysqli_real_escape_string($dbc, $_POST['incident_date']);
                $student_date = mysqli_real_escape_string($dbc, $_POST['student_date']);
                $suspended_date = mysqli_real_escape_string($dbc, $_POST['suspended_date']);
                $parent_date = mysqli_real_escape_string($dbc, $_POST['parent_date']);
                $recommendation = mysqli_real_escape_string($dbc, $_POST['recommendation']);
                $num_days = mysqli_real_escape_string($dbc, $_POST['num_days']);
                $final_date = mysqli_real_escape_string($dbc, $_POST['final_date']);
                $iep = $_POST['iep'];
                $query = "INSERT INTO hearings (student_id, username, behavior, parent, phone, incident_date, student_date, suspended_date, parent_date, recommendation, num_days, final_date, iep, requested_date, status)";
                $query .= " VALUES ($student_id, '$username', '$behavior', '$parent', '$phone', '$incident_date', '$student_date', '$suspended_date', '$parent_date', '$recommendation', $num_days, '$final_date', '$iep', CURDATE(), 'Requested')";
                mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
                $hearing_id = mysqli_insert_id($dbc);
                $target_dir = "hearings/";
                $files = array(
                    array('type' => 'student_statement', 'name' => 'Student Statement'),
                    array('type' => 'discipline', 'name' => 'Disciple Record'),
                    array('type' => 'attendance', 'name' => 'Attendance Record'),
                    array('type' => 'grades', 'name' => 'Grades'),
                    array('type' => 'counselor', 'name' => 'Counselor Referral'),
                    array('type' => 'statement-1', 'name' => 'Witness Statement 1'),
                    array('type' => 'statement-2', 'name' => 'Witness Statement 2'),
                    array('type' => 'statement-3', 'name' => 'Witness Statement 3')
                );
                foreach ($files as $file) {
                    $file_name = $_FILES[$file['type']]['name'];
                    if ($file_name != '') {
                        $file_tmp = $_FILES[$file['type']]['tmp_name'];
                        while (file_exists($target_dir . basename($file_name))) {
                            $file_name = "$hearing_id " . $file_name;
                        }
                        if (!move_uploaded_file($file_tmp, $target_dir . $file_name)) {
                            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> There was a problem uploading your files.</div>';
                        }
                        else {
                            $name = $file['name'];
                            $query = "INSERT INTO hearing_docs (hearing_id, type, name) VALUES ($hearing_id, '$name', '$file_name')";
                            mysqli_query($dbc, $query) or die(json_encode(array('status' => 'fail', 'message' => mysqli_error($dbc), 'query' => $query)));
                        }
                    }
                }
                $to_array = array();
                $query = "SELECT firstname, lastname FROM student_list WHERE student_id = $student_id";
                $result = mysqli_query($dbc, $query);
                $row = mysqli_fetch_array($result);
                $student = $row['firstname'] . ' ' . $row['lastname'];
                $subject = 'Hearing Requested - ' . $student;
                $user = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
                $message = "$user has requested a hearing for $student. You can view the details of this hearing " . '<a href="www.sblwilliams.com/hollandale/hearingdetails.php?id=' . $hearing_id . '">here</a>.';
                array_push($to_array, array($user, $username . '@hollandalesd.org'));
                array_push($to_array, array('Sam Williams', 'swilliams@hollandalesd.org'));
                array_push($to_array, array('Pat McGee', 'pmcgee@hollandalesd.org'));
                include('mail.php');
                if ($mail->Send()) {
                    ?>
                    <div class="alert alert-success">
                        Your hearing was successfully requested.
                    </div>
                    <?php
                }
                else {
                    ?>
                    <div class="alert alert-danger">
                        There was an error requesting your hearing.
                    </div>
                    <?php
                }
            }
            ?>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                <h3>Student Information</h3>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="student_id">Student</label>
		            	<select class="form-control" id="student_id" name="student_id" required>
							<option disabled selected></option>
		              		<?php
							$query = "SELECT firstname, lastname, grade, student_id FROM student_list ORDER BY lastname, firstname";
							$result = mysqli_query($dbc, $query);
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="' . $row['student_id'] . '">' . $row['lastname'] . ', ' . $row['firstname'] . ' (Gr. ' . $row['grade'] . ')</option>';
							}
		              		?>
		            	</select>
		          	</div>
                    <div class="form-group col-md-6">
                        <label for="behavior">Behavior for which student is being suspended/expelled</label>
                        <input type="text" maxlength="60" class="form-control" name="behavior" id="behavior" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="parent">Parent/Guardian Name</label>
                        <input type="text" maxlength="40" class="form-control" name="parent" id="parent" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone">Parent/Guardian Phone Number</label>
                        <input type="tel" class="form-control" name="phone" id="phone" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="incident_date">Date of Incident</label>
                        <input type="date" class="form-control" name="incident_date" id="incident_date" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="student_date">Date Student Informed</label>
                        <input type="date" class="form-control" name="student_date" id="student_date" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="suspended_date">Date Student Suspended</label>
                        <input type="date" class="form-control" name="suspended_date" id="suspended_date" required>
                        <small class="muted-text">The date the student is issued a suspension, not the first day of suspension.</small>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="parent_date">Date Parent Informed</label>
                        <input type="date" class="form-control" name="parent_date" id="parent_date" required>
                        <small class="muted-text">The parent should be informed in writing and in person or over the phone.</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="recommendation">School's Recommendation</label>
		            	<select class="form-control" id="recommendation" name="recommendation" required>
							<option disabled selected></option>
                            <option value="Alternative School for xx days">Alternative School for xx number of days</option>
                            <option value="Alternative School through xx">Alternative School through (date)</option>
		              		<option value="Expelled for xx days">Expelled for xx number of days</option>
                            <option value="Expelled through xx">Expelled through (date)</option>
		            	</select>
		          	</div>
                    <div class="form-group col-md-6" id="dateDiv" style="display: none;">
                        <label for="final_date">Final Date</label>
                        <input type="date" class="form-control" name="final_date" id="final_date">
                    </div>
                    <div class="form-group col-md-6" id="daysDiv" style="display: none;">
                        <label for="num_days">Number of Days</label>
                        <input type="number" class="form-control" name="num_days" id="num_days">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Is this student currently receiving special education or 504 services in the Hollandale School District or in the process of being evaluated to determine if said student is eligible for such services?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iep" id="iep1" value="Yes" required>
                            <label class="form-check-label" for="iep1">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="iep" id="iep2" value="No" required>
                            <label class="form-check-label" for="iep2">
                                No
                            </label>
                        </div>
                    </div>
                    <div class="form-group col-md-6 iepAlert" style="display: none;">
                        <div class="alert alert-danger iepAlert" style="display: none;">
                            This student must have a manifestation meeting prior to this hearing. If the behavior is found to be a manifestation of the student's disability, then the hearing will be cancelled if the behavior is a not a state-level zero tollerance offense.
                        </div>
                    </div>
                </div>
                <h3>Required Files</h3>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="student_statement" name="student_statement" required>
							<label class="custom-file-label" for="student_statement">Student's Statement...</label>
						</div>
					</div>
                    <div class="col-md-4">
                        <a href="studentstatementform.pdf" target="_blank">Student Statement Form</a>
                    </div>
				</div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="discipline" name="discipline" required>
							<label class="custom-file-label" for="discipline">Student's Discipline Record...</label>
						</div>
					</div>
				</div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="attendance" name="attendance" required>
							<label class="custom-file-label" for="attendance">Student's Attendance Record...</label>
						</div>
					</div>
				</div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="grades" name="grades" required>
							<label class="custom-file-label" for="grades">Student's Grades...</label>
						</div>
					</div>
				</div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="counselor" name="counselor" required>
							<label class="custom-file-label" for="counselor">Counselor Referral...</label>
						</div>
					</div>
                    <div class="col-md-4">
                        <a href="counselorreferral.pdf" target="_blank">Counselor Referral Form</a>
                    </div>
				</div>
                <h3>Optional Files</h3>
                <div class="alert alert-info">
                    It is not required to have statements from witnesses. However, it is recommended that you have multiple witness statements.
                </div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="statement-1" name="statement-1">
							<label class="custom-file-label" for="statement-1">Statement 1...</label>
						</div>
					</div>
				</div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="statement-2" name="statement-2">
							<label class="custom-file-label" for="statement-2">Statement 2...</label>
						</div>
					</div>
				</div>
                <div class="form-group row">
					<div class="col-md-5">
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="statement-3" name="statement-3">
							<label class="custom-file-label" for="statement-3">Statement 3...</label>
						</div>
					</div>
				</div>
                <div class="alert alert-info" id="submitAlert" style="display: none;">
                    Please wait. Files are uploading.
                </div>
                <button class="btn btn-primary" type="submit" id="submit">Submit form</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("input[name='iep']").click(function() {
        if ($(this).val() == 'Yes') {
            $(".iepAlert").show();
        }
        else {
            $(".iepAlert").hide();
        }
    });

    $("#submit").click(function() {
        $("#submitAlert").show();
    });

    $("#recommendation").on("change", function() {
        if ($(this).val().indexOf('days') > 0) {
            $("#daysDiv").show();
            $("#dateDiv").hide();
        }
        else {
            $("#daysDiv").hide();
            $("#dateDiv").show();
        }
        checkStatus();
    });

    $("input[type='file']").on("change", function() {
		var fieldVal = $(this).val();
		fieldVal = fieldVal.substr(12);
		if (fieldVal != undefined || fieldVal != "") {
			$(this).next(".custom-file-label").text(fieldVal);
		}
	});

    $(".form-check-input").on("click", function() {
        console.log('change');
        $(this).parent().siblings('.required').addClass("text-success")
        $(this).parent().siblings('.required').removeClass("text-danger");
        checkStatus();
    });

    $("input").on("blur", function() {
      if ($(this).val() === '') {
        $(this).siblings('.required').addClass("text-danger");
        $(this).siblings('.required').removeClass("text-success");
      }
      else {
        $(this).siblings('.required').removeClass("text-danger");
        $(this).siblings('.required').addClass("text-success");
        checkStatus();
      }
    });


});

function checkStatus() {
  if ($(".required:visible").hasClass("text-danger")) {
    $("#btnSubmit").attr("disabled", true);
  }
  else {
    $("#btnSubmit").attr("disabled", false);
  }
}
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
