<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Out of Town Travel Requests Report';
$page_access = 'Dept Head Admin Principal Superintendent Designee';
include('header.php');

//include other scripts needed here
echo '<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>';
echo '<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>';
echo '<script src="js/datatables_scripts.js"></script>';
echo '<script src="js/formsreports_scripts.js"></script>';

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
        Out of Town Travel
      </h1>
      <input type="hidden" id="form_type" value="outoftowntravel">
      <p class="lead">
        In the table below you will see all approved out of town travel requests.
      </p>
      <p>
        Click on a form to view and/or print it.
      </p>
		<?php

		$query = "SELECT f.form_id, location, program, firstname, lastname, travel_start, travel_end, check_id, outofstate FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) LEFT JOIN forms_outoftown AS town ON (f.form_id = town.formId) LEFT JOIN forms_outoftown_checks AS foc ON (f.form_id = foc.form_id) WHERE f.status = 'Approved' AND type = 'Out of Town Travel' ORDER BY travel_start DESC";
		$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-hover clickable dataTbl" id="tblApprove">
					<thead>
						<tr>
							<th></th>
							<th>Destination</th>
							<th>Employee</th>
							<th>Out of State</th>
							<th>Checks Needed</th>
							<th>Travel Dates</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td><a class="btn btn-secondary" href="forms/prints/outoftowntravel.php?formId=' . $row['form_id'] . '" target="_blank">View</a></td>';
							echo '<td>' . $row['program'] . '(' . $row['location'] . ')</td>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . $row['outofstate'] . '</td>';
							echo '<td>';
							if ($row['check_id'] !== null) {
								echo 'CHECK';
							}
							echo '</td>';
							echo '<td>' . makeDateAmerican($row['travel_start']) . ' - ' . makeDateAmerican($row['travel_end']) . '</td>';
							echo '</tr>';
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
