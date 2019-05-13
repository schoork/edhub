<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$form_type = mysqli_real_escape_string($dbc, trim($_GET['type']));

$page_title = 'Update Reading Tracker';
$page_access = 'All';
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
				Update Reading Tracker
			</h1>
			<p class="lead">
				Use this page to report students comleting and not completing their daily reading work.
			</p>
            <p>
                <a class="btn btn-secondary" href="readingdata.php">Reading Data</a>
            </p>
            <?php
            if (!isset($_GET['grade'])) {
                ?>
                <div class="form-group row">
                    <label for="grade" class="col-sm-3 col-form-label">Select Grade</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="grade">
                            <option disabled selected></option>
                            <option value="3">3rd Grade</option>
                            <option value="4">4th Grade</option>
                            <option value="6">6th Grade</option>
                        </select>
                    </div>
                </div>
                <?php
            } else {
                $grade = $_GET['grade'];
                ?>
                <p>
                    Grade Level: <?php echo $grade; ?>
                </p>
                <p>
                    Mark all students who have <strong>NOT</strong> turned in parent signature on their daily reading sheet.
                </p>
    			<form method="post" action="service.php">
    				<input type="hidden" name="action" value="updateReadingTracker">
    				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                    <?php
                    $query = "SELECT student_id, firstname, lastname, count(date) AS count FROM student_list AS sl LEFT JOIN readingtracker USING (student_id) WHERE grade = $grade GROUP BY sl.student_id ORDER BY lastname, firstname";
                    $result = mysqli_query($dbc, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="<?php echo $row['student_id']; ?>" name="students[]" id="student-<?php echo $row['student_id']; ?>">
                            <label class="form-check-label" for="student-<?php echo $row['student_id']; ?>">
                                <?php echo $row['lastname'] . ', ' . $row['firstname']; ?>
                            </label>
							<span style="display: none;" id="span-<?php echo $row['student_id']; ?>"> - <em>
								<?php
								if (($row['count'] % 3) == 2) {
									echo 'Mandatory Parent Conference (Admin)';
								} else if (($row['count'] % 3) == 0) {
									echo 'Student Conference';
								} else {
									echo 'Automated Call (Admin)';
								}
								?>
							</em></span>
                        </div>
                        <?php
                    }
                     ?>
    				<div id="alert" class="alert" role="alert">

    				</div>
    				<a class="btn btn-primary" href="#!" id="btnSubmitForm">Submit</a>
    				<a class="btn btn-danger" href="readingdata.php">Cancel</a>
    			</form>
                <?php
            }
            ?>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
    $("#grade").on("change", function() {
        var grade = $(this).val();
        window.location.href = 'readingtracker.php?grade=' + grade;
    });

	$(".form-check-input").on("change", function() {
		var id = $(this).prop("id").substring(8);
		if ($(this).prop("checked")) {
			$("#span-" + id).show();
		} else {
			$("#span-" + id).hide();
		}
	});

    $("#btnSubmitForm").click(function() {
        $("#alert").removeClass("alert-danger");
        $("#alert").addClass("alert-info");
        $("#alert").html('<strong>Please Wait!</strong> Your information is being submitted.');
        var data = $("form :input").serializeArray();
        $.post('service.php', data, function(json) {
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
                $("#alert").html("<strong>Well done!</strong> Your tracker data has been submitted.");
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
