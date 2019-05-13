<?php

$page_title = 'Substitute List';
$page_access = 'Principal Admin Designee Superintendent';
$page_type = 'Teachers';
include('header.php');

//include other scripts needed here
echo '<script src="js/forms_scripts.js"></script>';
echo '<script src="js/sublist_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-teachers.php');

?>

<?php

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_GET['date'])) {
	$date = mysqli_real_escape_string($dbc, $_GET['date']);
}
else {
	$date = date('Y-m-d');
}

?>

<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Teachers
		</h1>
		<p class="lead">
			Manage teachers, complete observations and forms, and view overall trends.
		</p>
	</div>
</div>
<div class="container mt-3 mb-3">
	<div class="row">
		<div class="col 12">
			<h1>
				Substitute List
			</h1>
			<p class="lead">
				From this page you can assign substitutes to teachers who are out.
			</p>
			<p>
				Assign a substitute by selecting his/her name in the dropdown box below the teacher/staff member's name. If no names are listed, then no subs have applied for that spot.
			</p>
			<form method="post" action="service.php" id="subForm">
				<input type="hidden" name="action" value="approveSubs">
				<div class="form-group row">
					<label for="date" class="col-2 col-form-label">Date</label>
					<div class="col-4">
					  <input class="form-control" type="date" value="<?php echo $date; ?>" id="date" name="date">
					</div>
				</div>
				<h2>
					Sanders Elementary
				</h2>
	      <p>
	        <?php
					$query = "SELECT staff_id, username, firstname, lastname, fto.type, school, start, end, sub, length FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE school = 'Sanders' AND start <= '$date' AND end >= '$date' ORDER BY school, lastname, firstname";
	        $result = mysqli_query($dbc, $query);
	        while ($row = mysqli_fetch_array($result)) {
	          echo '<strong>' . $row['firstname'] . ' ' . $row['lastname'] . '</strong> (' . $row['type'] . ' - ' . $row['length'] . ') - ' . makeDateAmerican($row['start']) . ' to ' . makeDateAmerican($row['end']) . '<br/>';
						if ($row['sub'] == 'Yes') {
							echo '<select class="custom-select" name="select-' . $row['username'] . '">';
		  				echo '<option value="-1">No sub assigned</option>';
							$username = $row['username'];
		  				$query = "SELECT sub_id, name, row_id, approved FROM sub_request AS sr LEFT JOIN sub_list AS sl USING (sub_id) WHERE employee = '$username' AND date = '$date' GROUP BY sub_id ORDER BY name";
							$data = mysqli_query($dbc, $query);
							if (mysqli_num_rows($data) > 0) {
								while ($line = mysqli_fetch_array($data)) {
									echo '<option value="' . $line['row_id'] . '"';
									if ($line['approved'] == 1) {
										echo ' selected';
									}
									echo '>' . $line['name'] . '</option>';
								}
							}
							echo '</select>';
						}
						else {
							echo '<em>No Sub Needed</em>';
						}
						echo '<br/>';
						echo '<br/>';
					}
	        ?>
				</p>
				<h2>
					Simmons Jr/Sr High
				</h2>
	      <p>
	        <?php
	        $query = "SELECT staff_id, username, firstname, lastname, fto.type, school, start, end, sub, length FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE school = 'Simmons' AND start <= '$date' AND end >= '$date' ORDER BY school, lastname, firstname";
	        $result = mysqli_query($dbc, $query);
	        while ($row = mysqli_fetch_array($result)) {
	          echo '<strong>' . $row['firstname'] . ' ' . $row['lastname'] . '</strong> (' . $row['type'] . ' - ' . $row['length'] . ') - ' . makeDateAmerican($row['start']) . ' to ' . makeDateAmerican($row['end']) . '<br/>';
						if ($row['sub'] == 'Yes') {
							echo '<select class="custom-select" name="select-' . $row['username'] . '">';
		  				echo '<option value="-1">No sub assigned</option>';
							$username = $row['username'];
							$query = "SELECT sub_id, name, row_id, approved FROM sub_request AS sr LEFT JOIN sub_list AS sl USING (sub_id) WHERE employee = '$username' AND date = '$date' GROUP BY sub_id ORDER BY name";
							$data = mysqli_query($dbc, $query);
							if (mysqli_num_rows($data) > 0) {
								while ($line = mysqli_fetch_array($data)) {
									echo '<option value="' . $line['row_id'] . '"';
									if ($line['approved'] == 1) {
										echo ' selected';
									}
									echo '>' . $line['name'] . '</option>';
								}
							}
							echo '</select>';
						}
						else {
							echo '<em>No Sub Needed</em>';
						}
						echo '<br/>';
						echo '<br/>';
					}
	        ?>
				</p>
				<div id="alert" class="alert" role="alert">

        </div>
				<p>
					<a class="btn btn-primary" href="#!" id="btnApprove">Send Notifications</a>

				</p>
			</form>
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
