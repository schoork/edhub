<?php

$page_title = 'Meeting Detail';
$page_access = 'All';
include('header.php');

//include other scripts needed here

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$meeting_id = $_GET['id'];
$username = $_SESSION['username'];
$query = "SELECT response FROM meetings_iep_staff WHERE meeting_id = $meeting_id AND username = '$username'";
$result = mysqli_query($dbc, $query);
if (mysqli_num_rows($result) == 0 && strpos('Admin Superintendent Principal', $_SESSION['access']) === false) {
    header("Location: https://www.sblwilliams.com/hollandale/restricted.php");
}
$response = mysqli_fetch_array($result)['response'];

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

$query = "SELECT firstname, lastname, username FROM meetings_iep LEFT JOIN staff_list USING (username) WHERE meeting_id = $meeting_id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);
$owner = $row['firstname'] . ' ' . $row['lastname'];
$owner_username = $row['username'];

$query = "SELECT firstname, lastname, time, date, notes, meeting_type FROM meetings_iep LEFT JOIN student_list USING (student_id) WHERE meeting_id = $meeting_id";
$result = mysqli_query($dbc, $query);
$row = mysqli_fetch_array($result);

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
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Meeting Detail
			</h1>
            <p>
                <a class="btn btn-secondary" href="meetings.php">Meetings</a>
                <a class="btn btn-secondary" href="addmeeting.php">Schedule Meeting</a>
            </p>
            <p>
                <strong>Organizer: </strong><?php echo $owner; ?><br>
                <strong>Student: </strong><?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br>
                <strong>Type: </strong><?php echo $row['meeting_type']; ?><br>
                <strong>Date: </strong><?php echo makeDateAmerican($row['date']); ?><br>
                <?php
                $time = new DateTime($row['time']);
                $display = $time->format('g:i A');
                ?>
                <strong>Time: </strong><?php echo $display; ?><br>
                <strong>Notes: </strong><?php echo $row['notes']; ?><br>
                <br>
                <strong>Staff Attending:</strong>
                <ul>
                    <?php
                    $query = "SELECT firstname, lastname, response FROM meetings_iep_staff LEFT JOIN staff_list USING (username) WHERE meeting_id = $meeting_id ORDER BY lastname, firstname";
                    $result = mysqli_query($dbc, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            echo '<li>' . $row['firstname'] . ' ' . $row['lastname'] . ' - ' . $row['response'] . '</li>';
                        }
                    }
                    ?>
                </ul>
            </p>
            <?php
            if ($response != '') {
                ?>
                <form method="post" action="service.php" id="responseForm">
                    <input type="hidden" name="action" value="meetingResponse">
                    <input type="hidden" name="meeting_id" value="<?php echo $meeting_id; ?>">
    				<input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="response" id="response1" value="Attending" <?php if ($response == 'Attending') { echo 'checked'; } ?>>
                                <label class="form-check-label" for="response1">
                                    Attending
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="response" id="response2" value="Not Attending" <?php if ($response == 'Not Attending') { echo 'checked'; } ?>>
                                <label class="form-check-label" for="response2">
                                    Not Attending
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="alert" class="alert" role="alert">

    				</div>
    				<a class="btn btn-primary" href="#!" id="btnSubmit">Submit</a>
                </form>
                <?php
            }
            if ($owner_username == $_SESSION['username']) {
                ?>
                <form method="post" action="service.php" id="cancelForm">
                    <input type="hidden" name="action" value="meetingCancel">
                    <input type="hidden" name="user" value="<?php echo $_SESSION['firstname'] . ' '. $_SESSION['lastname']; ?>">
                    <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                    <input type="hidden" name="meeting_id" value="<?php echo $meeting_id; ?>">
                    <div id="alert" class="alert" role="alert">

    				</div>
    				<a class="btn btn-danger" href="#!" id="btnCancelMeeting">Cancel Meeting</a>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#btnSubmit").click(function() {
        var data = $("#responseForm :input").serializeArray();
        $.post('service.php', data, function(json) {
            if (json.status == 'fail') {
                $(this).attr('disabled', false);
                $("#alert").addClass("alert-danger");
                $("#alert").removeClass("alert-info");
                $("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
                console.log(json.message);
            }
            else if (json.status == 'success') {
                $(this).attr('disabled', false);
                $("#alert").addClass("alert-success");
                $("#alert").removeClass("alert-info");
                $("#alert").html("<strong>Well done!</strong> Your response has been submitted.");
            }
        }, "json");
    });

    $("#btnCancelMeeting").click(function() {
        var data = $("#cancelForm :input").serializeArray();
        $.post('service.php', data, function(json) {
            if (json.status == 'fail') {
                $(this).attr('disabled', false);
                $("#alert").addClass("alert-danger");
                $("#alert").removeClass("alert-info");
                $("#alert").html("<strong>Stop!</strong> The information didn't update properly. Try again.");
                console.log(json.message);
            }
            else if (json.status == 'success') {
                $(this).attr('disabled', false);
                $("#alert").addClass("alert-success");
                $("#alert").removeClass("alert-info");
                $("#alert").html("<strong>Well done!</strong> Your meeting has been cancelled.");
            }
        }, "json");
    });
});
</script>


<?php

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
