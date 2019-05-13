<?php

$page_title = 'Hearing Details';
$page_access = 'All';
include('header.php');

//include other scripts needed here

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$hearing_id = $_GET['id'];

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

$query = "SELECT stud.firstname AS sfn, stud.lastname AS sln, sl.firstname, sl.lastname, parent, phone, behavior, recommendation, num_days, final_date, incident_date, student_date, suspended_date, parent_date, requested_date, h.status, iep FROM hearings AS h LEFT JOIN student_list AS stud ON (h.student_id = stud.student_id) LEFT JOIN staff_list AS sl ON (h.username = sl.username) WHERE hearing_id = $hearing_id";
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
				Hearing Details
			</h1>
            <p>
                <a class="btn btn-secondary" href="hearings.php">Hearings</a>
                <a class="btn btn-secondary" href="addmeeting.php">Schedule Meeting</a>
            </p>
            <p>
                <strong>Administrator: </strong><?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br>
                <strong>Student: </strong><?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br>
                <strong>Incident Date: </strong><?php echo makeDateAmerican($row['incident_date']); ?><br>
                <strong>Behavior: </strong><?php echo $row['behavior']; ?><br>
                <strong>Recommendation: </strong>
                <?php
                $recommend = $row['recommendation'];
                if (strpos($recommend, "days")) {
                    echo str_replace("xx", $row['num_days'], $recommend);
                }
                else {
                    echo str_replace("xx", makeDateAmerican($row['final_date']), $recommend);
                }
                ?>
            </p>
            <hr>
            <p>
                <strong>Incident Date: </strong><?php echo makeDateAmerican($row['incident_date']); ?><br>
                <strong>Date Student Informed: </strong><?php echo makeDateAmerican($row['student_date']); ?><br>
                <strong>Date Student Suspended: </strong><?php echo makeDateAmerican($row['suspended_date']); ?><br>
                <strong>Date Parent Informed: </strong><?php echo makeDateAmerican($row['parent_date']); ?><br>
                <strong>IEP, 504, or Testing: </strong><?php echo $row['iep']; ?>
            </p>
            <hr>
            <p>
                <strong>Formal Charge: </strong><?php echo $row['behavior']; ?><br>
                <strong>Recommendation: </strong>
                <?php
                $recommend = $row['recommendation'];
                if (strpos($recommend, "days")) {
                    echo str_replace("xx", $row['num_days'], $recommend);
                }
                else {
                    echo str_replace("xx", makeDateAmerican($row['final_date']), $recommend);
                }
                ?>
            </p>
            <hr>
            <h5>
                Files
            </h5>
            <p>
                Three (3) copies of the following documents should be made prior to the hearing. The copies will be made by the district office. A copy will be provided to the parent, the student, and the district hearing chair. All committee members will be responsible for bringing their own copies or using the digital ones below.
            </p>
            <p>
                <?php
                $query = "SELECT type, name FROM hearing_docs WHERE hearing_id = $hearing_id";
                $result = mysqli_query($dbc, $query);
                while ($row = mysqli_fetch_array($result)) {
                    echo '<a href="hearings/' . $row['name'] . '" target="_blank">' . $row['type'] . '</a><br>';
                }
                ?>
            </p>
            <p>
                The following files are for use during the hearing by the district hearing chair. Only one copy is needed.
            </p>
            <p>
                <a href="hearingscript.docx" target="_blank">Hearing Script</a><br>
                <a href="decisionform.pdf" target="_blank">Hearing Committee Decision Form</a>
            </p>
        </div>
    </div>
</div>


<?php

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
