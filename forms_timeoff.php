<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Time Off Requests Report';
$page_access = 'Dept Head Admin Principal Superintendent Designee';
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
include('navbar-district.php');
 
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			District Forms
		</h1>
    <p class="lead">
      Add, edit, and manage forms
    </p>
	</div>
</div>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-12">
      <h1>
        Time Off Requests
      </h1>
      <input type="hidden" id="form_type" value="timeoffrequest">
      <p class="lead">
        In the table below you will see all approve time off requests.
      </p>
      <p>
        Click on a form to view and/or print it.
      </p>
		<?php

		$query = "SELECT form_id, start, end, firstname, lastname, toff.type, school, f.status FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) LEFT JOIN forms_timeoff AS toff ON (f.form_id = toff.formId) WHERE f.type = 'Time Off Request' ORDER BY start DESC";
		$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-hover clickable dataTbl" id="tblApprove">
					<thead>
						<tr>
							<th></th>
							<th>Form ID</th>
							<th>Date(s)</th>
							<th>Employee</th>
							<th>Absence Type</th>
							<th>School</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td><a href="forms/prints/timeoffrequest.php?formId=' . $row['form_id'] . '" class="btn btn-secondary" target="_blank">View</a></td>';
							echo '<td>' . $row['form_id'] . '</td>';
							echo '<td>' . makeDateAmerican($row['start']);
							if ($row['start'] != $row['end']) {
								echo ' - ' . makeDateAmerican($row['end']);
							}
							echo '</td>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . $row['type'] . '</td>';
							echo '<td>' . $row['school'] . '</td>';
							echo '<td>' . $row['status'] . '</td></tr>';
						}
						?>
					</tbody>
				</table>
				<?php
			}
			else {
				echo '<p>No forms of this type have been approved as of yet.</p>';
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
