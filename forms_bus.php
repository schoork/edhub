<?php

require_once('connectvars.php');
require_once('appfunctions.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$page_title = 'Bus Requests Report';
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
        Bus Requests
      </h1>
      <input type="hidden" id="form_type" value="busrequest">
      <p class="lead">
        In the table below you will see all approved bus requests.
      </p>
      <p>
        Click on a form to view and/or print it.
      </p>
		<?php

		$query = "SELECT form_id, firstname, lastname, title, f.status, location, travel_start, travel_end, drivers, driver_num, drivers_assigned FROM forms AS f LEFT JOIN staff_list AS sl ON (f.employee = sl.username) LEFT JOIN forms_busrequest AS fr ON (f.form_id = fr.formId) WHERE f.status NOT LIKE 'Denied%' AND type = 'Bus Request' ORDER BY travel_start DESC";
		$result = mysqli_query($dbc, $query);
			if (mysqli_num_rows($result) > 0) {
				?>
				<table class="table table-hover clickable dataTbl" id="tblApprove">
					<thead>
						<tr>
							<th></th>
							<th>Employee</th>
							<th>Reason</th>
							<th>Destination</th>
							<th>Leaving</th>
							<th>Returning</th>
							<th>Drivers (Provided / Assigned)</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = mysqli_fetch_array($result)) {
							echo '<tr>';
							echo '<td><a class="btn btn-secondary" href="forms/prints/busrequest.php?formId=' . $row['form_id'] . '" target="_blank">View</a></td>';
							echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
							echo '<td>' . $row['title'] . '</td>';
							echo '<td>' . $row['location'] . '</td>';
							echo '<td>' . parseDatetime($row['travel_start']) . '</td>';
							echo '<td>' . parseDatetime($row['travel_end']) . '</td>';
							echo '<td>' . $row['drivers'] . ' / ';
							if ($row['drivers_assigned'] !== null) {
								echo $row['drivers_assigned'];
							}
							else {
								echo $row['driver_num'];
							}
							echo '</td>';
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
