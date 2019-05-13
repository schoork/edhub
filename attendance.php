<?php

$page_title = 'Attendance';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="/js/moment.js"></script>';
echo '<script rel="text/javascript" src="js/attendance_scripts.js"></script>';

//end header
echo '</head>';

//start body
echo '<body>';

//include nav bar
include('navbar-classes.php');

require_once('appfunctions.php');
require_once('connectvars.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>
<div class="bd-pageheader bg-primary text-white pt-4 pb-4">
	<div class="container">
		<h1>
			Classes
		</h1>
		<p class="lead">
			Manage classes and input attendance.
		</p>
	</div>
</div>
<?php
if (!isset($_GET['id']) || !isset($_GET['type'])) {
	?>
	<div class="container mt-3">
		<div class="row">
			<div class="col-12">
				<form>
					<div class="form-group">
						<label for="course_search">Select a course</label>
						<select id="course_search" class="form-control">
							<option disabled selected></option>
							<?php
							$query = "SELECT course_id, course FROM courses WHERE course_id <> -1 ORDER BY course";
							$result = mysqli_query($dbc, $query);
							while ($row = mysqli_fetch_array($result)) {
								echo '<option value="' . $row['course_id'] . '-course">' . $row['course'] . '</option>';
							}
							?>
						</select>
					</div>
				</form>
			</div>
		</div>
	</div>
	<?php
}
else {
	$id = $_GET['id'];
	$type = $_GET['type'];
	if ($type == 'course') {
		$query = "SELECT course AS name FROM courses WHERE course_id = $id";
	}
	else {
		$query = "SELECT club AS name FROM clubs WHERE club_id = $id";
	}
	$result = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($result);
	$class_name = $row['name'];

	?>
	<div class="container mt-3 mb-3">
		<div class="row">
			<div class="col 12">
				<h1>
					Attendance - <?php echo $class_name; ?>
				</h1>
				<p class="lead">
					Use this page to take period by period attendance for a specific date.
				</p>
				<p>
					Daily attendance is managed at the school level.
				</p>
				<p>
					<?php
					echo '<a href="rosters.php?id=' . $id . '&type=' . $type . '" class="btn btn-outline-primary">View Roster</a> ';
          ?>
				</p>
				<div class="alert alert-danger" id="nostudents-alert" role="alert" style="display: none;">
					<strong>Oh snap!</strong> There are no students enrolled.
				</div>
				<form method="post" action="service.php" id="att_form">
					<input type="hidden" name="action" value="takeAttendance">
					<input type="hidden" name="id" value="<?php echo $id ?>" id="id">
					<input type="hidden" name="type" value="<?php echo $type ?>" id="type">
					<div class="form-group">
						<label for="date" class="form-label">Date</label>
						<input class="form-control" type="date" name="date" value="<?php echo date('Y-m-d'); ?>" id="date">
					</div>
					<table class="table table-responsive table-striped table-bordered" id="att_tbl">
						<thead>
							<tr>
								<th>Student</th>
								<th>After-School</th>
							</tr>
							<tr>
								<td></td>
								<td><a class="btn btn-outline-primary mark-present" id="present-C" href="#!">Present</a></td>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
					<div id="alert" class="alert" role="alert">

					</div>
					<a class="btn btn-primary text-white" href="#!" id="btnSubmitForm">
						Submit
					</a>
					<a class="btn btn-outline-danger" href="attendance.php">
						Cancel
					</a>
				</form>
			</div>
		</div>
	</div>


<?php
}

mysqli_close($dbc);

//end body
echo '</body>';

//include footer
include('footer.php');

?>
