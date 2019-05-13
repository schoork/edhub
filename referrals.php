<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Referrals';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';


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
				Unassigned Referrals
			</h1>
			<p class="lead">
				In the table below you will see all active referrals.
			</p>
			<p>
				Click on a referral to view and/or resolve it.
			</p>
			<p>
				<a class="btn btn-primary" href="addreferral.php">Add Referral</a>
				<a class="btn btn-secondary" href="completed_referrals.php">Assigned Referrals</a>
				<a class="btn btn-secondary" href="referrals_data_sanders.php">Sanders Referral Data</a>
				<a class="btn btn-secondary" href="referrals_data_simmons.php">Simmons Referral Data</a>
			</p>
			<?php

			if ($_SESSION['school'] != 'District Office') {
				$school = $_SESSION['school'];
				$query = "SELECT firstname, lastname, rowid, teacher, time, behavior, status, action, grade FROM referrals LEFT JOIN student_list USING (student_id) WHERE school = '$school' AND status = 1 ORDER BY time DESC";
			}
			else {
				$query = "SELECT firstname, lastname, rowid, teacher, time, behavior, status, action, grade FROM referrals LEFT JOIN student_list USING (student_id) WHERE status = 1 ORDER BY time DESC";
			}
			$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Student</th>
							<th>Grade</th>
							<th>Teacher</th>
							<th>Date/Time</th>
							<th>Behavior</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td><a class="btn btn-secondary" href="viewreferral.php?id=' . $row['rowid'] .'">View</a></td>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . $row['grade'] . '</td>';
							echo '<td>' . $row['teacher'] . '</td>';
							echo '<td>' . parseRefDT($row['time']) . '</td>';
							echo '<td>' . $row['behavior'] . '</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else {
				echo '<p>No referrals have been submitted as of yet.</p>';
			}
			?>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {

	$('table').dataTable({
		"order": [[4, 'dsc']],
		"pageLength": 50
	});
});
</script>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
