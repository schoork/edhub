<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Daily Teacher Absences';
$page_access = 'All';
include('header.php');

//include other scripts needed here

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

$school = $_SESSION['school'];
$reasons = array(
    'Bereavement',
    'FMLA',
    'Jury Duty',
    'Personal',
    'Professional',
    'Sick',
    'Vacancy',
    'Unapproved'
);
 
?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
    <div class="container">
        <h1>
            Teachers
        </h1>
        <p class="lead">
            Manage teachers and forms
        </p>
    </div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
			<h1>
				Daily Teacher Absences
			</h1>
			<p class="lead">
				Use this page to report teacher absences and subsitute work days.
			</p>
			<p>
				<a class="btn btn-primary" href="referrals.php">Referrals</a>
			</p>
			<form method="post" action="service2.php">
				<input type="hidden" name="action" value="addTeacherAbsences">
                <input type="hidden" name="school" value="<?php echo $_SESSION['school']; ?>">
				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                <div id="teacherAbsencesDiv">
                    <div class="form-row" id="copyRow">
                        <div class="form-group col-3">
                            <label for="teacher-1">Teacher</label>
                            <select name="teacher-1" id="teacher-1" class="custom-select">
                                <option disabled selected></option>
                                <?php
                                $query = "SELECT firstname, lastname, staff_id FROM staff_list WHERE school = '$school' ORDER BY lastname, firstname";
                                $result = mysqli_query($dbc, $query);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<option value="' . $row['staff_id'] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-3">
                            <label for="reason-1">Reason</label>
                            <select name="reason-1" id="reason-1" class="custom-select">
                                <option disabled selected></option>
                                <?php
                                foreach ($reasons as $reason) {
                                    echo '<option value="' . $reason . '">' . $reason . '</option>';
                                }
                                 ?>
                             </select>
                         </div>
                         <div class="form-group col-3">
                             <label for="length-1">Length</label>
                             <select name="length-1" id="length-1" class="custom-select">
                                 <option value="1">Full Day</option>
                                 <option value="0.5">Half Day</option>
                              </select>
                          </div>
                         <div class="form-group col-3">
                             <label for="sub-1">Substitute</label>
                             <select name="sub-1" id="sub-1" class="custom-select">
                                 <option selected value="0">No Sub</option>
                                 <?php
                                 $query = "SELECT name, sub_id FROM sub_list ORDER BY name";
                                 $result = mysqli_query($dbc, $query);
                                 while ($row = mysqli_fetch_array($result)) {
                                     echo '<option value="' . $row['sub_id'] . '">' . $row['name'] . '</option>';
                                 }
                                 ?>
                             </select>
                         </div>
                    </div>
                </div>
                <p>
                    <a class="btn btn-outline-primary" href="#!" id="addTeacher">Add Teacher</a>
                    <a class="btn btn-outline-danger disabled" href="#!" id="removeTeacher">Remove Teacher</a>
                    <input type="hidden" name="teacherNumber" id="teacherNumber" value="1">
                </p>
				<div id="alert" class="alert" role="alert">

				</div>
				<a class="btn btn-primary" href="#!" id="btnSubmitForm">Submit</a>
				<a class="btn btn-danger" href="referrals.php">Cancel</a>
			</form>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
    var $clone = $("#copyRow").clone();
    var i = 1;

    $("#addTeacher").click(function() {
        i++;
        $("#teacherNumber").val(i);
        var $div = $clone.clone();
        $("#teacherAbsencesDiv").append($div);
        $($div).find("select", "label").each(function() {
            var id = $(this).attr("id").substring(0, $(this).attr("id").length - 1);
            $(this).attr("id", id + i);
            $(this).attr("name", id + i);
            $(this).attr("for", id + i);
        });
        $("#removeTeacher").removeClass("disabled");
    });

    $("#removeTeacher").click(function() {
        i--;
        $("#teacherNumber").val(i);
        $("#teacherAbsencesDiv").children().last().remove();
        if (i == 1) {
            $("#removeTeacher").addClass("disabled");
        }
    });

    $("#btnSubmitForm").click(function() {
        $("#alert").removeClass("alert-danger");
        $("#alert").addClass("alert-info");
        $("#alert").html('<strong>Please Wait!</strong> Your information is being submitted.');
        var data = $("form :input").serializeArray();
        $.post('service2.php', data, function(json) {
            if (json.status == 'fail') {
                $(this).attr('disabled', false);
                $("#alert").addClass("alert-danger");
                $("#alert").removeClass("alert-info");
                $("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
                console.log(json.message);
            } else if (json.status == 'success') {
                $(this).attr('disabled', false);
                $("#alert").addClass("alert-success");
                $("#alert").removeClass("alert-info");
                $("#alert").html("<strong>Well done!</strong> Your form has been submitted.");
            }
        }, "json");
    });

});
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
