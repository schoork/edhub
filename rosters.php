<?php

$page_title = 'Attendance';
$page_access = 'All';
include('header.php');

//include other scripts needed here
echo '<script src="js/rosters_scripts.js"></script>';

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
	$query = "SELECT course AS name FROM courses WHERE course_id = $id";
	$result = mysqli_query($dbc, $query);
	$row = mysqli_fetch_array($result);
	$class_name = $row['name'];

	?>
	<div class="container mt-3 mb-3">
		<div class="row">
			<div class="col 12">
				<h1>
					Roster - <?php echo $class_name ?>
				</h1>
				<p class="lead">
					This page displays the roster for a specific class or club.
				</p>
        <p>
          Click on a student to see their student page.
        </p>
				<p>
					<?php
					echo '<a href="attendance.php?id=' . $id . '&type=' . $type . '" class="btn btn-outline-primary">Take Attendance</a> ';
					?>
				</p>

        <?php
        $query = "SELECT student_id, name, c.course FROM school_enrollment AS se LEFT JOIN courses AS c ON (se.course = c.course_id) WHERE course_id = $id AND status = 1 ORDER BY name";
        $result = mysqli_query($dbc, $query);
        if (mysqli_num_rows($result) > 0) {
          $i = 1;
          ?>
          <table class="table table-responsive table-hover">
            <thead>
              <tr>
                <th>Student Name</th>
                <th>Course</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($row = mysqli_fetch_array($result)) {
                echo '<tr id="student-' . $row['student_id'] . '"><td>' . $i . '. ' . $row['name'] . '</td><td>' . $row['course'] . '</td><td>' . $row['club'] . '</td></tr>';
                $i++;
              }
              ?>
            </tbody>
          </table>
          <p>
            Total Enrollment: <?php echo $i - 1; ?>
          </p>
          <?php
        }
        else {
          ?>
          <div class="alert alert-info" id="nostudents-alert" role="alert">
            <strong>Oh snap!</strong> There are no students enrolled.
          </div>
          <?php
        }
        ?>
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
