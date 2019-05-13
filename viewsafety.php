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

$safety_id = mysqli_real_escape_string($dbc, trim($_GET['id']));

$query = "SELECT firstname, lastname, si.school, date_time, location, staff_present, staff_name, description, injuries, actions_taken, actions_taken_desc, contacts FROM safety_incidents AS si LEFT JOIN staff_list USING (username) WHERE incident_id = $safety_id";
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
				Safety Incident #<?php echo $safety_id; ?>
			</h1>
      <p>
        Submitted by: <?php echo $row['firstname'] . ' ' . $row['lastname']; ?><br>
        <br>
        Students Involved: <br>
        <?php
        $query = "SELECT firstname, lastname FROM safety_incidents_students LEFT JOIN student_interventions USING (student_id) WHERE incident_id = $safety_id";
        $result = mysqli_query($dbc, $query);
        while ($line = mysqli_fetch_array($result)) {
          echo $line['firstname'] . ' ' . $line['lastname'] . '<br>';
        }
        ?>
        <br>
        School: <?php echo $row['school']; ?><br>
        Incident Date/Time: <?php echo parseDatetime($row['date_time']); ?><br>
        Location: <?php echo $row['school']; ?><br>
        Staff Present: <?php echo $row['staff_present']; ?><br>
        Staff Name: <?php echo $row['staff_name']; ?><br>
        <br>
        Description: <?php echo $row['description']; ?><br>
        <br>
        Injuries: <?php echo $row['injuries']; ?><br>
        <br>
        Actions Taken: <?php echo $row['actions_taken']; ?><br>
        Description of Actions Taken: <?php echo $row['actions_taken_desc']; ?><br>
        <br>
        People Contacted: <?php echo $row['contacts']; ?><br>
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
