<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Observations';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Teachers
		</h1>
	    <p class="lead">
	    	Manage and observe teachers
	    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
			<h1>
				Observations
			</h1>
			<p class="lead">
				In the table below you will see all completed observations.
			</p>
			<p>
				Click on an observation to view it.
			</p>
			<p>
				<a class="btn btn-primary" href="addobservation.php">Add Observation</a>
			</p>
			<?php

			if ($_SESSION['access'] == 'Superintendent' || $_SESSION['access'] == 'Admin') {
				$query = "SELECT row_id, sl.firstname, sl.lastname, sls.firstname AS tch_first, sls.lastname AS tch_last, date, dom1, dom2, dom3, overall FROM observations AS obs LEFT JOIN staff_list AS sl ON (obs.observer = sl.username) LEFT JOIN staff_list AS sls ON (obs.teacher = sls.username) ORDER BY row_id DESC";
			}
			else if ($_SESSION['access'] == 'Principal') {
                $username = $_SESSION['username'];
				$school = $_SESSION['school'];
				$query = "SELECT row_id, sl.firstname, sl.lastname, sls.firstname AS tch_first, sls.lastname AS tch_last, date, dom1, dom2, dom3, overall FROM observations AS obs LEFT JOIN staff_list AS sl ON (obs.observer = sl.username) LEFT JOIN staff_list AS sls ON (obs.teacher = sls.username) WHERE sl.school = '$school' ORDER BY row_id DESC";
				echo $query;
			}
			else if ($_SESSION['access'] != 'Teacher') {
                $username = $_SESSION['username'];
				$query = "SELECT row_id, sl.firstname, sl.lastname, sls.firstname AS tch_first, sls.lastname AS tch_last, date, dom1, dom2, dom3, overall FROM observations AS obs LEFT JOIN staff_list AS sl ON (obs.observer = sl.username) LEFT JOIN staff_list AS sls ON (obs.teacher = sls.username) WHERE observer = '$username' ORDER BY row_id DESC";
			}
            else {
                $username = $_SESSION['username'];
                $query = "SELECT row_id, sl.firstname, sl.lastname, sls.firstname AS tch_first, sls.lastname AS tch_last, date, dom1, dom2, dom3, overall FROM observations AS obs LEFT JOIN staff_list AS sl ON (obs.observer = sl.username) LEFT JOIN staff_list AS sls ON (obs.teacher = sls.username) WHERE teacher = '$username' ORDER BY row_id DESC";
            }
			$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-hover table-striped dataTbl" id="tblApprove">
					<thead>
						<tr>
							<th></th>
							<th>Teacher</th>
							<th>Observer</th>
							<th>Date</th>
							<th>Domain I</th>
							<th>Domain II</th>
                            <th>Domain III</th>
                            <th>Overall</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td><a class="btn btn-secondary" href="viewobservation.php?obs=' . $row['row_id'] . '">View</a></td>';
                            echo '<td>' . $row['tch_first'] . ' ' . $row['tch_last'] . '</td>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . makeDateAmerican($row['date']) . '</td>';
                            echo '<td>' . $row['dom1'] . '</td>';
                            echo '<td>' . $row['dom2'] . '</td>';
                            echo '<td>' . $row['dom3'] . '</td>';
                            echo '<td>' . $row['overall'] . '</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else {
				echo '<p>No observations have been completed as of yet.</p>';
			}
			?>
		</div>
	</div>
</div>

<?php

mysqli_close($dbc);

//include footer
include('footer.php');
?>
