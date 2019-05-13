<?php

$page_title = 'Substitute List';
$page_access = 'All';
$page_type = 'Teachers';
include('header.php');

//include other scripts needed here


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
				From this page you can see all teachers and staff members.
			</p>
			<form method="post" action="service.php" id="subForm">
				<input type="hidden" name="action" value="approveSubs">
	      <p>
	        <?php
	        $query = "SELECT staff_id, username, firstname, lastname, fto.type, school, start, end FROM staff_list AS sl LEFT JOIN forms AS f ON (sl.username = f.employee) LEFT JOIN forms_timeoff AS fto ON (f.form_id = fto.formId) WHERE start <= CURDATE() AND end >= CURDATE() AND sub <> 'No' ORDER BY school, lastname, firstname";
	        $result = mysqli_query($dbc, $query);
	        while ($row = mysqli_fetch_array($result)) {
	          echo $row['firstname'] . ' ' . $row['lastname'] . ' ' . $row['username'] . ' (' . $row['school'] . ') - ' . makeDateAmerican($row['start']) . ' to ' . makeDateAmerican($row['end']) . ' for ' . $row['type'] . '<br/>';
						echo '<select class="custom-select col" name="select-' . $row['username'] . '">';
	  				echo '<option selected disabled></option>';
						$username = $row['username'];
	  				$query = "SELECT name, sub_id FROM sub_request AS sr LEFT JOIN sub_list AS sl USING (sub_id) WHERE employee = '$username' AND date = CURDATE()";
						$data = mysqli_query($dbc, $query);
						if (mysqli_num_rows($data) > 0) {
							while ($line = mysqli_fetch_array($data)) {
								echo '<option value="' . $line['sub_id'] . '">' . $line['name'] . '</option>';
							}
						}
						echo '</select>';
						echo '<br/>';
						echo '<br/>';
					}
	        ?>
				</p>
				<div id="alert" class="alert" role="alert">

        </div>
				<p>
					<a class="btn btn-primary" href="#!" id="btnFormSubmit">Send Notifications</a>

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
