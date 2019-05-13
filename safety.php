<?php

$page_title = 'Safety Incidents';
$page_access = 'Admin Superintendent Dept Head Principal Designee';
include('header.php');

//include other scripts needed here
?>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
<script src="js/datatables_scripts.js"></script>

<?php

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-students.php');

?>

<?php

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
				Safety Incidents
			</h1>
			<p class="lead">
				From this page you can see all submitted safety incidents.
			</p>
      <p>
        <a class="btn btn-primary" href="addsafety.php">Add Safety Incident</a>
			</p>
      <table class="table table-sm table-hover mt-4 dataTbl">
        <thead>
			<tr>
				<th></th>
				<th>Date/Time</th>
				<th>Student(s)</th>
				<th>School</th>
				<th>Location</th>
				<th>Staff Present</th>
				<th>Injuries</th>
				<th>Contacts</th>
			</tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT date_time, school, location, incident_id, staff_present, staff_name, injuries, contacts FROM safety_incidents ORDER BY date_time DESC";
          $result = mysqli_query($dbc, $query);
          while ($row = mysqli_fetch_array($result)) {
			  			$incident_id = $row['incident_id'];
            			echo '<tr">';
						echo '<td><a class="btn btn-secondary" href="viewsafety.php?id=' . $incident_id . '">View</a></td>';
						echo '<td>' . parseDatetime($row['date_time']) . '</td>';

						$query = "SELECT firstname, lastname FROM safety_incidents_students LEFT JOIN student_interventions USING (student_id) WHERE incident_id = $incident_id";
						$data = mysqli_query($dbc, $query);
						$students = '';
						while ($line = mysqli_fetch_array($data)) {
							$students .= $line['firstname'] . ' ' . $line['lastname'] . ', ';
						}
						$students = substr($students, 0, strlen($students) - 2);
						echo '<td>' . $students . '</td>';
						echo '<td>' . $row['school'] . '</td>';
						echo '<td>' . $row['location'] . '</td>';
						if ($row['staff_present'] == 'No') {
							echo '<td>No</td>';
						}
						else {
							echo '<td>' . $row['staff_name'] . '</td>';
						}
						if (strlen($row['injuries']) > 30) {
							echo '<td>' . substr($row['injuries'], 0, 27) . '...</td>';
						}
						else {
							echo '<td>' . $row['injuries'] . '</td>';
						}
						echo '<td>' . $row['contacts'] . '</td>';
						echo '</tr>';
          }
          ?>
        </tbody>
      </table>
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
