<?php

$page_title = 'Disciplinary Hearings';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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
				Disciplinary Hearings
			</h1>
			<p class="lead">
				From this page you can see all requested disciplinary hearings.
			</p>
            <p>
                A disciplinary hearing is required due process for any student being expelled from school, recommended for alternative school placement, and/or suspended for a period longer than ten (10) days. A parent/guardian can waive his/her child's right to a disciplinary hearing using <a href="waiverform.pdf" target="_blank">this form</a>.
            </p>
            <p>
                <a class="btn btn-secondary" href="hearingrequest.php">Request Hearing</a>
            </p>
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Student</th>
                        <th>Administrator</th>
                        <th>Requested Date</th>
                        <th>Incident Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT stud.firstname AS sfn, stud.lastname AS sln, sl.firstname, sl.lastname, incident_date, requested_date, h.status, hearing_id FROM hearings AS h LEFT JOIN student_list AS stud ON (h.student_id = stud.student_id) LEFT JOIN staff_list AS sl ON (h.username = sl.username)";
                    $result = mysqli_query($dbc, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                <td><a class="btn btn-secondary" href="hearingdetails.php?id=<?php echo $row['hearing_id']; ?>">View</a></td>
                                <td><?php echo $row['sfn'] . ' ' . $row['sln']; ?></td>
                                <td><?php echo $row['firstname'] . ' ' . $row['lastname']; ?></td>
                                <td><?php echo makeDateAmerican($row['requested_date']); ?></td>
                                <td><?php echo makeDateAmerican($row['incident_date']); ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("table").DataTable();
});
</script>

<?php

mysqli_close($dbc);


//end body
echo '</body>';

//include footer
include('footer.php');
?>
